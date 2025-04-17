<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Advanced Chaotic Image Encryption</title>
  <style>
    :root {
      --primary: #4a6bff;
      --secondary: #6c5ce7;
      --accent: #fd79a8;
      --dark: #2d3436;
      --light: #f5f6fa;
      --success: #00b894;
      --danger: #d63031;
    }
    
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #dfe6e9 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      text-align: center;
      padding: 20px;
      color: var(--dark);
      min-height: 100vh;
    }

    h1, h2 {
      color: var(--primary);
      margin-bottom: 20px;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      background: rgba(255,255,255,0.9);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .section {
      margin: 40px 0;
      padding: 30px;
      background: rgba(245, 245, 245, 0.7);
      border-radius: 12px;
      border-left: 5px solid var(--primary);
    }

    .panel {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin: 30px 0;
    }

    .image-panel {
      flex: 1;
      min-width: 300px;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s ease;
    }

    .image-panel:hover {
      transform: translateY(-5px);
    }

    input[type="file"] {
      display: none;
    }

    .file-upload {
      padding: 12px 25px;
      background: var(--primary);
      color: white;
      border-radius: 8px;
      cursor: pointer;
      display: inline-block;
      margin: 10px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .file-upload:hover {
      background: var(--secondary);
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    button {
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      background: var(--dark);
      color: white;
      cursor: pointer;
      margin: 10px;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    button:disabled {
      background: #b2bec3;
      cursor: not-allowed;
      transform: none !important;
      box-shadow: none !important;
    }

    .btn-encrypt {
      background: var(--secondary);
    }

    .btn-decrypt {
      background: var(--success);
    }

    .btn-download {
      background: var(--accent);
    }

    img {
      max-width: 100%;
      height: auto;
      max-height: 300px;
      border: 2px solid #ddd;
      border-radius: 8px;
      margin-top: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    img:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    canvas {
      display: none;
    }

    .key-info {
      margin: 20px 0;
      padding: 15px;
      background: rgba(74, 107, 255, 0.1);
      border-radius: 8px;
      border-left: 4px solid var(--primary);
    }

    .status {
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      font-weight: 500;
    }

    .status-success {
      background: rgba(0, 184, 148, 0.2);
      color: var(--success);
    }

    .status-error {
      background: rgba(214, 48, 49, 0.2);
      color: var(--danger);
    }

    .divider {
      height: 2px;
      background: linear-gradient(to right, transparent, var(--primary), transparent);
      margin: 40px auto;
      width: 80%;
    }

    /* Feedback Form Styles */
    #feedbackForm input, #feedbackForm textarea, #feedbackForm select {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #ddd;
      margin-bottom: 15px;
      font-family: inherit;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    #feedbackForm input:focus, #feedbackForm textarea:focus, #feedbackForm select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.2);
    }

    #feedbackForm textarea {
      min-height: 100px;
      resize: vertical;
    }

    #feedbackForm button[type="submit"] {
      width: 100%;
      background: var(--primary);
      font-size: 16px;
      padding: 14px;
      margin-top: 10px;
    }

    #feedbackForm button[type="submit"]:hover {
      background: var(--secondary);
    }

    @media (max-width: 768px) {
      .container {
        padding: 15px;
      }
      .section {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🔐 Advanced Chaotic Image Encryption</h1>
    <p>Secure your images using chaotic logistic map encryption with key dataset management</p>

    <div class="section">
      <h2>🔒 Encryption Section</h2>
      
      <label for="imageInput" class="file-upload">
        <i class="fas fa-upload"></i> Upload Original Image
      </label>
      <input type="file" id="imageInput" accept="image/*">
      
      <div class="panel">
        <div class="image-panel">
          <h3>Original Image</h3>
          <img id="originalImage" src="https://via.placeholder.com/400x300?text=Upload+Original+Image" />
        </div>
        
        <div class="image-panel">
          <h3>Grayscale Image</h3>
          <img id="grayImage" src="https://via.placeholder.com/400x300?text=Grayscale+Image" />
        </div>
        
        <div class="image-panel">
          <h3>Encrypted Image</h3>
          <img id="encryptedImage" src="https://via.placeholder.com/400x300?text=Encrypted+Image" />
        </div>
      </div>
      
      <button id="encryptBtn" class="btn-encrypt">Encrypt Image</button>
      <button id="downloadEncryptedBtn" class="btn-download" disabled>Download Encrypted Image</button>
      <button id="downloadKeyBtn" class="btn-download" disabled>Download Key Dataset</button>
      
      <div class="key-info">
        <h3>🔑 Key Information</h3>
        <p>Your encryption key dataset will be generated after encryption.</p>
        <p id="keyStatus">No key generated yet</p>
      </div>
    </div>

    <div class="divider"></div>

    <div class="section">
      <h2>🔓 Decryption Section</h2>
      <p>To decrypt, you must upload both the encrypted image and the key dataset</p>
      
      <div style="margin: 20px 0;">
        <label for="encryptedImageInput" class="file-upload">
          <i class="fas fa-upload"></i> Upload Encrypted Image
        </label>
        <input type="file" id="encryptedImageInput" accept="image/*">
        
        <label for="keyDatasetInput" class="file-upload">
          <i class="fas fa-key"></i> Upload Key Dataset
        </label>
        <input type="file" id="keyDatasetInput" accept=".txt,.csv">
      </div>
      
      <div class="panel">
        <div class="image-panel">
          <h3>Encrypted Input</h3>
          <img id="uploadedEncryptedImage" src="https://via.placeholder.com/400x300?text=Upload+Encrypted+Image" />
        </div>
        
        <div class="image-panel">
          <h3>Decrypted Image</h3>
          <img id="decryptedImage" src="https://via.placeholder.com/400x300?text=Decrypted+Image" />
        </div>
      </div>
      
      <button id="decryptBtn" class="btn-decrypt">Decrypt Image</button>
      <div id="decryptStatus" class="status"></div>
    </div>

    <div class="divider"></div>

    <div class="section">
      <h2>💬 Feedback Form</h2>
      <p>Help us improve this tool by sharing your experience</p>
      
      <form id="feedbackForm" action="./submit_feedback.php" method="POST">
          <div style="max-width: 600px; margin: 0 auto; text-align: left;">
              <div style="margin-bottom: 15px;">
                  <label for="username" style="display: block; margin-bottom: 5px; font-weight: 500;">Username</label>
                  <input type="text" id="username" name="username" required maxlength="20">
              </div>
              
              <div style="margin-bottom: 15px;">
                  <label for="regno" style="display: block; margin-bottom: 5px; font-weight: 500;">Registration Number</label>
                  <input type="number" id="regno" name="regno" required>
              </div>
              
              <div style="margin-bottom: 15px;">
                  <label for="section" style="display: block; margin-bottom: 5px; font-weight: 500;">Section</label>
                  <input type="text" id="section" name="section" required maxlength="30">
              </div>
              
              <div style="margin-bottom: 15px;">
                  <label for="text" style="display: block; margin-bottom: 5px; font-weight: 500;">Your Feedback</label>
                  <textarea id="text" name="text" rows="4" required maxlength="100"></textarea>
              </div>
              
              <button type="submit" class="btn-submit">Submit Feedback</button>
          </div>
      </form>
      
      <div id="feedbackStatus" class="status" style="margin-top: 20px;"></div>
  </div>
  </div>

  <canvas id="canvas"></canvas>
  <?php
  $pdo = new PDO("mysql:host=127.0.0.1;dbname=feedbackform", "root", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
  echo "Connected successfully!";
  ?>
  <script>
    // DOM Elements
    const imageInput = document.getElementById('imageInput');
    const encryptBtn = document.getElementById('encryptBtn');
    const downloadKeyBtn = document.getElementById('downloadKeyBtn');
    const downloadEncryptedBtn = document.getElementById('downloadEncryptedBtn');
    const encryptedImageInput = document.getElementById('encryptedImageInput');
    const keyDatasetInput = document.getElementById('keyDatasetInput');
    const decryptBtn = document.getElementById('decryptBtn');
    const originalImage = document.getElementById('originalImage');
    const grayImage = document.getElementById('grayImage');
    const encryptedImage = document.getElementById('encryptedImage');
    const uploadedEncryptedImage = document.getElementById('uploadedEncryptedImage');
    const decryptedImage = document.getElementById('decryptedImage');
    const keyStatus = document.getElementById('keyStatus');
    const decryptStatus = document.getElementById('decryptStatus');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const feedbackForm = document.getElementById('feedbackForm');
    const feedbackStatus = document.getElementById('feedbackStatus');

    // Variables
    let originalImageData;
    let chaoticSequence = [];
    let keyBlobUrl = null;

    // Initialize the application
    function init() {
        setupEventListeners();
        loadFontAwesome();
    }

    // Set up all event listeners
    function setupEventListeners() {
        imageInput.addEventListener('change', handleOriginalImageUpload);
        encryptBtn.addEventListener('click', encryptImage);
        downloadEncryptedBtn.addEventListener('click', downloadEncryptedImage);
        downloadKeyBtn.addEventListener('click', downloadKeyDataset);
        encryptedImageInput.addEventListener('change', handleEncryptedImageUpload);
        keyDatasetInput.addEventListener('change', handleKeyDatasetUpload);
        decryptBtn.addEventListener('click', decryptImage);
        feedbackForm.addEventListener('submit', handleFeedbackSubmit);
    }

    // Load Font Awesome icons
    function loadFontAwesome() {
        const faScript = document.createElement('script');
        faScript.src = 'https://kit.fontawesome.com/a076d05399.js';
        document.head.appendChild(faScript);
    }

    // Handle original image upload
    function handleOriginalImageUpload() {
        const file = imageInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            originalImage.src = e.target.result;
            
            const img = new Image();
            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                originalImageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            };
            img.src = e.target.result;
            
            showStatus(keyStatus, "Ready for encryption", "status-success");
        };
        reader.readAsDataURL(file);
    }

    // Encrypt the image using chaotic sequence
    function encryptImage() {
        if (!originalImageData) {
            showStatus(keyStatus, "Please upload an image first", "status-error");
            return;
        }

        const width = canvas.width;
        const height = canvas.height;
        const pixels = originalImageData.data;

        // Convert to grayscale
        for (let i = 0; i < pixels.length; i += 4) {
            const avg = Math.round((pixels[i] + pixels[i + 1] + pixels[i + 2]) / 3);
            pixels[i] = pixels[i + 1] = pixels[i + 2] = avg;
        }

        // Store grayscale version
        const grayImageData = new ImageData(new Uint8ClampedArray(pixels), width, height);
        ctx.putImageData(grayImageData, 0, 0);
        grayImage.src = canvas.toDataURL();

        // Generate chaotic sequence (logistic map)
        chaoticSequence = generateChaoticSequence(width * height);

        // XOR grayscale with chaotic key
        const encryptedData = encryptPixels(pixels, chaoticSequence);

        const encryptedImageData = new ImageData(encryptedData, width, height);
        ctx.putImageData(encryptedImageData, 0, 0);
        encryptedImage.src = canvas.toDataURL();

        // Enable download buttons
        downloadEncryptedBtn.disabled = false;
        downloadKeyBtn.disabled = false;

        // Prepare key dataset download
        prepareKeyDatasetDownload();

        showStatus(keyStatus, "Encryption complete! Download your key dataset", "status-success");
    }

    // Generate chaotic sequence using logistic map
    function generateChaoticSequence(length) {
        const sequence = [];
        let x = 0.5;
        const r = 3.99;

        for (let i = 0; i < length; i++) {
            x = r * x * (1 - x);
            sequence.push(Math.floor(x * 256)); // 0-255
        }

        return sequence;
    }

    // Encrypt pixels using XOR with chaotic sequence
    function encryptPixels(pixels, sequence) {
        const encryptedData = new Uint8ClampedArray(pixels);
        for (let i = 0, j = 0; i < encryptedData.length; i += 4, j++) {
            const xorVal = sequence[j];
            encryptedData[i] ^= xorVal;
            encryptedData[i + 1] ^= xorVal;
            encryptedData[i + 2] ^= xorVal;
        }
        return encryptedData;
    }

    // Prepare the key dataset for download
    function prepareKeyDatasetDownload() {
        const content = chaoticSequence.join('\n');
        const blob = new Blob([content], { type: 'text/plain' });
        keyBlobUrl = URL.createObjectURL(blob);
    }

    // Download encrypted image
    function downloadEncryptedImage() {
        const a = document.createElement('a');
        a.href = encryptedImage.src;
        a.download = "encrypted_image.png";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    // Download key dataset
    function downloadKeyDataset() {
        if (!keyBlobUrl) return;
        
        const a = document.createElement('a');
        a.href = keyBlobUrl;
        a.download = 'chaotic_key_dataset.txt';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    // Handle encrypted image upload
    function handleEncryptedImageUpload() {
        const file = encryptedImageInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            uploadedEncryptedImage.src = e.target.result;
            showStatus(decryptStatus, "Encrypted image uploaded", "status-success");
        };
        reader.readAsDataURL(file);
    }

    // Handle key dataset upload
    function handleKeyDatasetUpload() {
        const file = keyDatasetInput.files[0];
        if (!file) return;

        showStatus(decryptStatus, "Key dataset uploaded", "status-success");
    }

    // Decrypt the image using the key dataset
    function decryptImage() {
        const encryptedFile = encryptedImageInput.files[0];
        const keyFile = keyDatasetInput.files[0];

        if (!encryptedFile || !keyFile) {
            showStatus(decryptStatus, "Please upload both encrypted image and key dataset", "status-error");
            return;
        }

        showStatus(decryptStatus, "Decrypting...", "status-success");

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                let encryptedData = ctx.getImageData(0, 0, img.width, img.height);
                const pixelData = encryptedData.data;

                // Read chaotic dataset
                const keyReader = new FileReader();
                keyReader.onload = function() {
                    try {
                        const keyValues = keyReader.result.trim().split('\n').map(Number);

                        if (keyValues.length < (img.width * img.height)) {
                            showStatus(decryptStatus, "Key dataset is too short for this image", "status-error");
                            return;
                        }

                        // Decrypt pixels
                        for (let i = 0, j = 0; i < pixelData.length; i += 4, j++) {
                            const xorVal = keyValues[j];
                            pixelData[i] ^= xorVal;
                            pixelData[i + 1] ^= xorVal;
                            pixelData[i + 2] ^= xorVal;
                        }

                        ctx.putImageData(encryptedData, 0, 0);
                        decryptedImage.src = canvas.toDataURL();
                        showStatus(decryptStatus, "Decryption successful!", "status-success");
                    } catch (error) {
                        showStatus(decryptStatus, "Error processing key dataset", "status-error");
                        console.error(error);
                    }
                };
                keyReader.readAsText(keyFile);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(encryptedFile);
    }

    // Enhanced feedback form submission handler
    // Enhanced feedback form submission handler
// In your index.html file, update the handleFeedbackSubmit function:
// function handleFeedbackSubmit(e) {
//     e.preventDefault();
    
//     // Show loading state
//     feedbackStatus.textContent = "Submitting feedback...";
//     feedbackStatus.className = "status";
    
//     // Create FormData from form
//     const formData = new FormData(feedbackForm);
    
//     // Convert FormData to JSON
//     const jsonData = {};
//     formData.forEach((value, key) => jsonData[key] = value);
    
//     fetch(feedbackForm.action, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'Accept': 'application/json'
//         },
//         body: JSON.stringify(jsonData)
//     })
//     .then(async response => {
//         const data = await response.json();
//         if (!response.ok) {
//             throw new Error(data.error || 'Server error');
//         }
//         return data;
//     })
//     .then(data => {
//         feedbackStatus.textContent = data.success;
//         feedbackStatus.className = "status status-success";
//         feedbackForm.reset();
//     })
//     .catch(error => {
//         console.error('Error:', error);
//         feedbackStatus.textContent = error.message || "Error submitting feedback";
//         feedbackStatus.className = "status status-error";
//     });
// }

    // Show status message
    function showStatus(element, message, className) {
        element.textContent = message;
        element.className = "status " + className;
    }

    // Initialize the application
    init();
</script>

</body>

</html>