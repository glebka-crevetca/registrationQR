function checkQRCodeLibrary() {
  if (typeof QRCode === "undefined") {
    console.error("Библиотека QRCode не загружена");
    alert(
      "Библиотека QRCode не загружена. Пожалуйста, перезагрузите страницу."
    );
    return false;
  }
  console.log("Библиотека QRCode успешно загружена");
  return true;
}

function generateQRCode(text, elementId) {
  const container = document.getElementById(elementId);
  if (!container) {
    console.error("Элемент для QR-кода не найден:", elementId);
    return null;
  }

  container.innerHTML = "";

  try {
    const qrcode = new QRCode(container, {
      text: text,
      width: 380,
      height: 380,
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H,
    });

    return container.querySelector("canvas");
  } catch (error) {
    console.error("Ошибка генерации QR-кода:", error);
    container.innerHTML = `<div style="padding: 20px; border: 1px solid #ccc; text-align: center;">
      <p>QR-код: ${text}</p>
      <p>Сохраните эту ссылку</p>
    </div>`;
    return null;
  }
}

document
  .getElementById("clientForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    if (!checkQRCodeLibrary()) {
      return;
    }

    const formData = new FormData(this);
    const formDataObj = Object.fromEntries(formData.entries());

    try {
      console.log("Отправка данных клиента:", formDataObj);

      const response = await fetch("register_client.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formDataObj),
      });

      if (!response.ok) {
        throw new Error(`Ошибка HTTP: ${response.status}`);
      }

      const data = await response.json();
      console.log("Ответ сервера:", data);

      if (data.success) {
        const qrData = `${window.location.origin}/admin.html#client_id=${data.id}`;
        const canvas = generateQRCode(qrData, "clientQrcode");

        if (canvas) {
          const downloadLink = document.getElementById("clientDownloadLink");
          downloadLink.href = canvas.toDataURL("image/png"); // Исправлено на PNG
          downloadLink.download = "qr_code_клиент.png";
          downloadLink.style.display = "inline-block";
        }

        document.getElementById("clientQrSection").style.display = "block";
        document
          .getElementById("clientQrSection")
          .scrollIntoView({ behavior: "smooth" });
      } else {
        throw new Error(data.message || "Ошибка регистрации");
      }
    } catch (error) {
      console.error("Ошибка:", error);
      alert("Ошибка регистрации: " + error.message);
    }
  });

document
  .getElementById("brokerForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    if (!checkQRCodeLibrary()) {
      alert("Библиотека QRCode не загружена. Перезагрузите страницу.");
      return;
    }

    const formData = new FormData(this);
    const formDataObj = Object.fromEntries(formData.entries());

    try {
      console.log("Отправка данных брокера:", formDataObj);

      const response = await fetch("register_broker.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formDataObj),
      });

      if (!response.ok) {
        throw new Error(`Ошибка HTTP: ${response.status}`);
      }

      const data = await response.json();
      console.log("Ответ сервера:", data);

      if (data.success) {
        const qrData = `${window.location.origin}/admin.html#broker_client_id=${data.id}`;
        const canvas = generateQRCode(qrData, "brokerQrcode");

        if (canvas) {
          const downloadLink = document.getElementById("brokerDownloadLink");
          downloadLink.href = canvas.toDataURL("image/png"); // Исправлено на PNG
          downloadLink.download = "qr_code_брокер.png";
          downloadLink.style.display = "inline-block";
        }

        document.getElementById("brokerQrSection").style.display = "block";
        document
          .getElementById("brokerQrSection")
          .scrollIntoView({ behavior: "smooth" });
      } else {
        throw new Error(data.message || "Ошибка регистрации");
      }
    } catch (error) {
      console.error("Ошибка:", error);
      alert("Ошибка регистрации: " + error.message);
    }
  });

document.addEventListener("DOMContentLoaded", function () {
  setTimeout(checkQRCodeLibrary, 1000);
});
