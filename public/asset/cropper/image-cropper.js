class ImageCropper {
    constructor(options) {
        this.inputId = options.inputId;
        this.previewId = options.previewId;
        this.croppedInputId = options.croppedInputId;
        this.cropButtonId = options.cropButtonId;
        this.aspectRatio = options.aspectRatio || 1;
        this.width = options.width || 300;
        this.height = options.height || 300;
        this.cropper = null;

        this.init();
    }

    init() {
        const input = document.getElementById(this.inputId);
        const cropButton = document.getElementById(this.cropButtonId);

        if (input) {
            input.addEventListener('change', this.handleImageSelect.bind(this));
        }

        if (cropButton) {
            cropButton.addEventListener('click', this.handleCrop.bind(this));
        }

        // Handle form submission
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', this.handleFormSubmit.bind(this));
        }
    }

    handleImageSelect(e) {
        const imageFile = e.target.files[0];
        if (imageFile) {
            const reader = new FileReader();
            reader.onload = (event) => {
                const previewImage = document.getElementById(this.previewId);
                previewImage.src = event.target.result;
                previewImage.style.display = 'block';

                // Destroy an existing cropper if it exists
                if (this.cropper) {
                    this.cropper.destroy();
                }

                // Initialize cropper
                this.cropper = new Cropper(previewImage, {
                    aspectRatio: this.aspectRatio,
                    viewMode: 1,
                    ready: () => {
                        this.cropper.setCropBoxData({
                            width: this.width,
                            height: this.height
                        });
                    }
                });

                // Show the crop button
                document.getElementById(this.cropButtonId).style.display = 'block';
            };
            reader.readAsDataURL(imageFile);
        }
    }

    handleCrop() {
        if (this.cropper) {
            const croppedCanvas = this.cropper.getCroppedCanvas({
                width: this.width,
                height: this.height
            });

            // Update preview with cropped image
            const previewImage = document.getElementById(this.previewId);
            previewImage.src = croppedCanvas.toDataURL();

            // Store cropped image data
            document.getElementById(this.croppedInputId).value = croppedCanvas.toDataURL();

            // Destroy cropper after getting the cropped image
            this.cropper.destroy();
            this.cropper = null;

            // Hide the crop button after cropping
            document.getElementById(this.cropButtonId).style.display = 'none';
        }
    }

    handleFormSubmit(e) {
        const croppedInput = document.getElementById(this.croppedInputId);
        if (croppedInput && croppedInput.value) {
            e.preventDefault();
            fetch(croppedInput.value)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], "cropped-image.jpg", { type: "image/jpeg" });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    document.getElementById(this.inputId).files = dataTransfer.files;
                    e.target.submit();
                });
        }
    }
}
