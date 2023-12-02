const label1 = document.getElementById('img1');
const label2 = document.getElementById('img2');
const label3 = document.getElementById('img3');

const input1 = document.getElementById('img1label');
const input2 = document.getElementById('img2label');
const input3 = document.getElementById('img3label');

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

label1.addEventListener('change', () => formatLabel("#img1", input1));
label2.addEventListener('change', () => formatLabel("#img2", input2));
label3.addEventListener('change', () => formatLabel("#img3", input3));
