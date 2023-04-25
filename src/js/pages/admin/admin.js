
import '../../../sass/admin/admin.scss';

const tx = document.getElementsByTagName("textarea");

for (let i = 0; i < tx.length; i++) {
    tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;");
    tx[i].addEventListener("input", autoText, false);
}

function autoText() {
    this.style.height = 0;
    this.style.height = (this.scrollHeight) + "px";
}