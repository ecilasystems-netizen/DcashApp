<div>
    @push('styles')
        <style>
            body {
                font-family: "Inter", sans-serif;
            }

            .brand-gradient {
                background: linear-gradient(135deg, #e1b362 0%, #d4a55a 100%);
            }

            #cameraFeed {
                transform: scaleX(-1);
            }

            #photoPreview {
                transform: scaleX(1);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener("livewire:navigated", () => {
                const video = document.getElementById("cameraFeed")
                const canvas = document.getElementById("photoCanvas")
                const photoPreview = document.getElementById("photoPreview")
                const instruction = document.getElementById("instruction")

                const startState = document.getElementById("startState")
                const captureState = document.getElementById("captureState")
                const previewState = document.getElementById("previewState")

                const startCameraButton = document.getElementById("startCameraButton")
                const captureButton = document.getElementById("captureButton")
                const retakeButton = document.getElementById("retakeButton")
                const confirmButton = document.getElementById("confirmButton")

                // Check if all elements are found
                if (!video || !canvas || !photoPreview || !instruction ||
                    !startState || !captureState || !previewState ||
                    !startCameraButton || !captureButton || !retakeButton || !confirmButton) {
                    console.error("One or more required elements not found in the DOM")
                    return
                }

                let stream = null

                async function startCamera() {
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: "user",
                                width: {ideal: 1280},
                                height: {ideal: 720}
                            },
                        })
                        video.srcObject = stream
                        await video.play() // Ensure video is playing
                        video.style.display = "block"
                        photoPreview.style.display = "none" // Fix: Change hidden to none

                        startState.classList.add("hidden")
                        captureState.classList.remove("hidden")
                        previewState.classList.add("hidden")

                    } catch (err) {
                        console.error("Error accessing camera: ", err)
                        instruction.textContent = "Could not access camera. Please check your browser permissions."
                        startState.classList.remove("hidden") // Keep the start button visible if camera fails
                    }
                }

                function stopCamera() {
                    if (stream) {
                        stream.getTracks().forEach((track) => track.stop())
                    }
                }

                startCameraButton.addEventListener("click", startCamera)

                captureButton.addEventListener("click", () => {
                    // Set canvas dimensions to match video
                    canvas.width = video.videoWidth
                    canvas.height = video.videoHeight

                    // Draw the current video frame to the canvas
                    const context = canvas.getContext("2d")
                    context.translate(canvas.width, 0)
                    context.scale(-1, 1)
                    context.drawImage(video, 0, 0, canvas.width, canvas.height)

                    // Show the captured image
                    const imageData = canvas.toDataURL("image/png")
                    photoPreview.src = imageData
                    photoPreview.style.display = "block" // Fix: Change classList to style
                    video.style.display = "none"

                    // Update UI state
                    captureState.classList.add("hidden")
                    previewState.classList.remove("hidden")
                    instruction.textContent = "Does this look good? You can retake the photo if needed."

                    // Stop the camera stream
                    stopCamera()
                })

                retakeButton.addEventListener("click", () => {
                    startCamera()
                    photoPreview.style.display = "none" // Fix: Change hidden to none
                    instruction.textContent = "Position your face in the center of the frame and take a clear selfie."
                })

                confirmButton.addEventListener("click", async () => {
                    // Store original button text
                    const originalConfirmText = confirmButton.innerHTML;
                    const spinner = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Submitting...`;

                    confirmButton.disabled = true
                    retakeButton.disabled = true
                    confirmButton.innerHTML = spinner
                    confirmButton.classList.add('flex', 'items-center', 'justify-center');


                    try {
                        const imageData = canvas.toDataURL("image/png")
                        await @this.
                        saveSelfie(imageData)

                        // On success, Livewire will redirect. If it doesn't, we update the UI.
                        instruction.textContent = "Selfie captured successfully! Processing..."
                        previewState.innerHTML = `<p class="text-green-400">Processing...</p>`

                    } catch (error) {
                        console.error('Submission failed:', error);
                        instruction.textContent = "Submission failed. Please try again.";
                        // Re-enable buttons on failure
                        confirmButton.disabled = false
                        retakeButton.disabled = false
                        confirmButton.innerHTML = originalConfirmText
                    }
                })
            })
        </script>
    @endpush
    <x-slot name="header">
        <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-700/80">
            <div class="px-4 lg:px-0 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{route('kyc.upload-documents')}}" class="text-gray-400 hover:text-white">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <div>
                        <p class="text-xs text-gray-400">KYC</p>
                        <h2 class="font-bold text-xl text-white">Liveness Check</h2>
                    </div>
                </div>
                <p class="text-sm font-semibold text-gray-400">Step 3 of 4</p>
            </div>
        </header>
    </x-slot>

    <div class="max-w-md mx-auto text-center">
        <p id="instruction" class="text-gray-400 mb-6">
            Position your face in the center of the frame and take a clear selfie.
        </p>

        <div
            class="relative w-full aspect-square rounded-full overflow-hidden bg-gray-800 border-4 border-gray-700 flex items-center justify-center mb-6">
            <video id="cameraFeed" class="w-full h-full object-cover" style="display: none;" autoplay
                   playsinline></video>
            <canvas id="photoCanvas" class="hidden"></canvas>
            <img id="photoPreview" src="" style="display: none;" class="w-full h-full object-cover" alt="Your Selfie"/>
        </div>

        <div id="actions">
            <div id="startState">
                <button id="startCameraButton"
                        class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                    <i data-lucide="video" class="inline-block mr-2 -mt-1"></i>
                    Start Camera
                </button>
            </div>

            <div id="captureState" class="hidden">
                <button id="captureButton"
                        class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                    <i data-lucide="camera" class="inline-block mr-2 -mt-1"></i>
                    Take Selfie
                </button>
            </div>

            <div id="previewState" class="hidden grid grid-cols-2 gap-4">
                <button id="retakeButton"
                        class="bg-gray-700 w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:bg-gray-600 transition-all">
                    Retake
                </button>
                <button id="confirmButton"
                        class="brand-gradient w-full text-white py-3 px-6 rounded-lg font-semibold text-base hover:opacity-90 transition-all">
                    Continue
                </button>
            </div>
        </div>
    </div>
</div>
