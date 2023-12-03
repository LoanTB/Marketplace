const label1 = document.getElementById('pfp');

const input1 = document.getElementById('pfpLabel');

const convert_to_base64 = file => new Promise((response) => {
    const file_reader = new FileReader();
    file_reader.readAsDataURL(file);
    file_reader.onload = () => response(file_reader.result);
});

async function formatLabel(selector, label){
    const file = document.querySelector(selector).files;
    const my_image = await convert_to_base64(file[0]);
    label.style.backgroundImage =`url(${my_image})`;
    label.classList.add("isImageHere");
}

label1.addEventListener('change', () => formatLabel("#pfp", input1));
