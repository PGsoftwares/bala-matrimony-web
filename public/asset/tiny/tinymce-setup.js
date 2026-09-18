// import tinymce from "../../tinymce/tinymce.min.js"; this is call for using page under import

const image_upload_handler = (blobInfo, success, failure) => {
    const editorId = tinymce.activeEditor.id;
    let uploadUrl = '';

    // Determine the correct upload route based on the editor ID
    if (editorId === 'editor') {
        uploadUrl = '{{ route("terms-and-conditions.image.store") }}?_token={{ csrf_token() }}';
    } else if (editorId === 'privacy-policy') {
        uploadUrl = '{{ route("privacy-policy-image") }}?_token={{ csrf_token() }}';
    } else if (editorId === 'about-us') {
        uploadUrl = '{{ route("about-us-image") }}?_token={{ csrf_token() }}';
    } else {
        failure('Unknown editor ID.');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.withCredentials = false;
    xhr.open('POST', uploadUrl);

    xhr.onload = () => {
        if (xhr.status < 200 || xhr.status >= 300) {
            failure('HTTP Error: ' + xhr.status);
            return;
        }

        try {
            const json = JSON.parse(xhr.responseText);
            if (!json || typeof json.url !== 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
            } else {
                success(json.url);
            }
        } catch (e) {
            failure('Error parsing response: ' + e.message);
        }
    };

    xhr.onerror = () => {
        failure('Image upload failed due to an XHR Transport error. Code: ' + xhr.status);
    };

    const formData = new FormData();
    formData.append('upload', blobInfo.blob(), blobInfo.filename());

    xhr.send(formData);
};


document.addEventListener('DOMContentLoaded', function () {
    tinymce.init({
        license_key: 'gpl',
        selector: '#editor, #privacy-policy, #refund-policy, #about-us', // Applying to both editors
        plugins: [
            'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
            'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
            'table', 'emoticons', 'help'
        ],
        toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
            'forecolor backcolor emoticons | help',
        images_upload_handler: image_upload_handler,
        automatic_uploads: true,
        file_picker_types: 'image',
        file_picker_callback: (cb, value, meta) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.addEventListener('change', (e) => {
                const file = e.target.files[0];

                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    const id = 'blobid' + (new Date()).getTime();
                    const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];

                    // Decode base64 to binary
                    const byteCharacters = atob(base64);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);

                    // Create a Blob object from the decoded data
                    const blob = new Blob([byteArray], { type: file.type });

                    // Create a blobInfo object
                    const blobInfo = blobCache.create(id, blob, base64);
                    blobCache.add(blobInfo);

                    // Callback with the blob URI and additional data (e.g., title)
                    cb(blobInfo.blobUri(), { title: file.name });
                });
                reader.readAsDataURL(file);
            });

            input.click();
        },

        menu: {
            favs: { title: 'Menu', items: 'code visualaid | searchreplace | emoticons' }
        },
        menubar: 'favs file edit view insert format tools table help',
        content_style: 'body { font-family: Helvetica, Arial, sans-serif; font-size: 16px; }'
    });
});
