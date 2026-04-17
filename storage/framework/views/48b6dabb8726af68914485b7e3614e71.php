<script>
    document.addEventListener('DOMContentLoaded', function () {
        var posterBlocks = document.querySelectorAll('[data-story-poster]');
        if (!posterBlocks.length) {
            return;
        }

        function isUrl(value) {
            return /^https?:\/\//i.test(value) || /^\/|^data:/i.test(value);
        }

        function getAppUrl() {
            if (AIZ && AIZ.data && AIZ.data.appUrl) {
                return AIZ.data.appUrl;
            }
            var meta = document.querySelector('meta[name="app-url"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function getCsrfToken() {
            if (AIZ && AIZ.data && AIZ.data.csrf) {
                return AIZ.data.csrf;
            }
            var meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function fetchFileInfo(ids) {
            if (typeof $ === 'undefined') {
                return Promise.resolve([]);
            }

            var appUrl = getAppUrl();
            if (!appUrl) {
                return Promise.resolve([]);
            }

            return new Promise(function (resolve) {
                $.post(
                    appUrl.replace(/\/$/, '') + "/aiz-uploader/get_file_by_ids",
                    { _token: getCsrfToken(), ids: ids },
                    function (data) {
                        resolve(Array.isArray(data) ? data : []);
                    }
                ).fail(function () {
                    resolve([]);
                });
            });
        }

        function normalizeFileUrl(file) {
            if (!file) {
                return null;
            }
            var url = file.file_url || file.file_name || file.file_path || file.file;
            if (!url) {
                return null;
            }
            if (isUrl(url)) {
                return url;
            }
            var appUrl = getAppUrl();
            if (appUrl) {
                return appUrl.replace(/\/$/, '') + '/' + String(url).replace(/^\//, '');
            }
            return url;
        }

        function resolveVideoUrl(value) {
            if (!value) {
                return Promise.resolve(null);
            }

            if (isUrl(value)) {
                return Promise.resolve(value);
            }

            var firstId = String(value).split(',')[0].trim();
            if (!firstId) {
                return Promise.resolve(null);
            }

            if (/\/|\.|uploads/i.test(firstId)) {
                return Promise.resolve(normalizeFileUrl({ file_name: firstId }));
            }

            return fetchFileInfo(firstId).then(function (files) {
                return files.length ? normalizeFileUrl(files[0]) : null;
            });
        }

        function captureFrame(video, canvas, time) {
            return new Promise(function (resolve) {
                var onSeeked = function () {
                    video.removeEventListener('seeked', onSeeked);
                    canvas.width = video.videoWidth || 360;
                    canvas.height = video.videoHeight || 640;
                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    resolve(canvas.toDataURL('image/jpeg', 0.92));
                };
                video.addEventListener('seeked', onSeeked);
                try {
                    video.currentTime = time;
                } catch (e) {
                    resolve(null);
                }
            });
        }

        posterBlocks.forEach(function (block) {
            var scope = block.closest('form') || document;
            var videoInput = scope.querySelector('.story-video-input');
            var coverInput = scope.querySelector('.story-cover-input');
            var posterDataInput = block.querySelector('.story-poster-data');
            var suggestedGrid = block.querySelector('.story-frame-grid');
            var suggestedEmpty = block.querySelector('.story-frame-empty');
            var selectedPreview = block.querySelector('.story-selected-preview');
            var videoPanel = block.querySelector('.story-video-panel');
            var videoPlayer = block.querySelector('.story-video-player');
            var videoRange = block.querySelector('.story-video-range');
            var captureBtn = block.querySelector('.story-capture-btn');
            var posterTabs = block.querySelectorAll('.story-poster-tab');
            var posterPanels = block.querySelectorAll('.story-poster-panel');

            if (!videoInput || !posterDataInput) {
                return;
            }

            var lastVideoValue = null;
            var videoUrl = null;

            function setSelectedPreview(dataUrl) {
                if (!selectedPreview) {
                    return;
                }
                if (!dataUrl) {
                    selectedPreview.innerHTML = '<div class="text-muted small"><?php echo e(translate("No poster selected yet.")); ?></div>';
                    return;
                }
                selectedPreview.innerHTML =
                    '<img src="' + dataUrl + '" class="img-fit rounded" style="width:90px;height:120px;object-fit:cover;border:1px solid #e5e7eb;">';
            }

            function clearUploaderPreview() {
                if (!coverInput) {
                    return;
                }
                coverInput.value = '';
                var previewBox = coverInput.closest('.input-group')?.nextElementSibling;
                if (previewBox && previewBox.classList.contains('file-preview')) {
                    previewBox.innerHTML = '';
                }
            }

            function handleFrameSelect(dataUrl) {
                posterDataInput.value = dataUrl || '';
                clearUploaderPreview();
                setSelectedPreview(dataUrl);
            }

            function renderSuggestedFrames(frames) {
                if (!suggestedGrid || !suggestedEmpty) {
                    return;
                }
                suggestedGrid.innerHTML = '';
                if (!frames.length) {
                    suggestedEmpty.classList.remove('d-none');
                    return;
                }
                suggestedEmpty.classList.add('d-none');
                frames.forEach(function (frame, index) {
                    if (!frame) {
                        return;
                    }
                    var button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'btn btn-light p-1 border story-frame-btn';
                    button.style.width = '90px';
                    button.style.height = '120px';
                    button.innerHTML = '<img src="' + frame + '" class="img-fit rounded" style="width:100%;height:100%;object-fit:cover;">';
                    button.addEventListener('click', function () {
                        var activeButtons = suggestedGrid.querySelectorAll('.story-frame-btn.active');
                        activeButtons.forEach(function (active) {
                            active.classList.remove('active');
                        });
                        button.classList.add('active');
                        handleFrameSelect(frame);
                    });
                    suggestedGrid.appendChild(button);
                });
            }

            function generateSuggestedFrames(url) {
                if (!videoPlayer || !suggestedGrid) {
                    return;
                }

                suggestedGrid.innerHTML = '';
                if (suggestedEmpty) {
                    suggestedEmpty.classList.remove('d-none');
                }

                if (!url) {
                    return;
                }

                var tempVideo = document.createElement('video');
                tempVideo.crossOrigin = 'anonymous';
                tempVideo.src = url;
                tempVideo.muted = true;
                tempVideo.playsInline = true;
                tempVideo.preload = 'metadata';

                tempVideo.addEventListener('loadedmetadata', function () {
                    var duration = tempVideo.duration || 0;
                    if (!duration || !isFinite(duration)) {
                        return;
                    }
                    var start = duration * 0.35;
                    var end = duration * 0.7;
                    var count = 8;
                    var times = [];
                    if (count === 1) {
                        times = [duration / 2];
                    } else {
                        for (var i = 0; i < count; i++) {
                            times.push(start + ((end - start) * i) / (count - 1));
                        }
                    }
                    var canvas = document.createElement('canvas');
                    var frames = [];
                    var chain = Promise.resolve();

                    times.forEach(function (time) {
                        chain = chain.then(function () {
                            return captureFrame(tempVideo, canvas, time).then(function (dataUrl) {
                                if (dataUrl) {
                                    frames.push(dataUrl);
                                }
                            });
                        });
                    });

                    chain.then(function () {
                        renderSuggestedFrames(frames);
                    });
                });

                tempVideo.addEventListener('error', function () {
                    if (suggestedEmpty) {
                        suggestedEmpty.textContent = 'Video preview is not available for this file.';
                        suggestedEmpty.classList.remove('d-none');
                    }
                });

                try {
                    tempVideo.load();
                } catch (e) {
                    // no-op
                }
            }

            function prepareVideoPanel(url) {
                if (!videoPanel || !videoPlayer || !videoRange) {
                    return;
                }
                if (!url) {
                    videoPanel.classList.add('d-none');
                    return;
                }

                videoPanel.classList.remove('d-none');
                videoPlayer.src = url;
                videoPlayer.crossOrigin = 'anonymous';
                videoPlayer.addEventListener('loadedmetadata', function () {
                    videoRange.max = Math.floor(videoPlayer.duration || 0);
                    videoRange.value = Math.floor((videoPlayer.duration || 0) / 2);
                    videoPlayer.currentTime = videoRange.value;
                }, { once: true });
            }

            function loadVideoFromInput() {
                var currentValue = videoInput.value;
                if (!currentValue || currentValue === lastVideoValue) {
                    return;
                }
                lastVideoValue = currentValue;
                resolveVideoUrl(currentValue).then(function (url) {
                    videoUrl = url;
                    generateSuggestedFrames(url);
                    prepareVideoPanel(url);
                });
            }

            if (coverInput) {
                coverInput.addEventListener('change', function () {
                    if (coverInput.value) {
                        posterDataInput.value = '';
                        setSelectedPreview('');
                    }
                });
            }

            if (videoRange && videoPlayer) {
                videoRange.addEventListener('input', function () {
                    videoPlayer.currentTime = Number(videoRange.value || 0);
                });
            }

            if (captureBtn && videoPlayer) {
                captureBtn.addEventListener('click', function () {
                    var canvas = document.createElement('canvas');
                    canvas.width = videoPlayer.videoWidth || 360;
                    canvas.height = videoPlayer.videoHeight || 640;
                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(videoPlayer, 0, 0, canvas.width, canvas.height);
                    handleFrameSelect(canvas.toDataURL('image/jpeg', 0.92));
                });
            }

            posterTabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    var target = this.getAttribute('data-target');
                    posterTabs.forEach(function (btn) {
                        btn.classList.remove('btn-soft-primary');
                        btn.classList.add('btn-soft-secondary');
                    });
                    this.classList.add('btn-soft-primary');
                    this.classList.remove('btn-soft-secondary');

                    posterPanels.forEach(function (panel) {
                        if (panel.getAttribute('data-panel') === target) {
                            panel.classList.remove('d-none');
                        } else {
                            panel.classList.add('d-none');
                        }
                    });
                });
            });

            setSelectedPreview(posterDataInput.value);

            if (videoInput.value) {
                loadVideoFromInput();
            }

            videoInput.addEventListener('change', loadVideoFromInput);
            setInterval(loadVideoFromInput, 1200);
        });
    });
</script>
<?php /**PATH C:\laragon\www\Murli_Devlopment\resources\views/partials/story_poster_script.blade.php ENDPATH**/ ?>