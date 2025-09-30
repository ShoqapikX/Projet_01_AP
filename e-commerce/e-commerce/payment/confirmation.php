<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande confirmée - Nike Basketball</title>
    <!-- Remontez d'un niveau pour accéder au dossier css -->
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .confirmation-container {
            max-width: 600px;
            margin: 100px auto;
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .success-icon {
            font-size: 64px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .countdown {
            font-weight: bold;
            color: #333;
        }
        .redirect-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="success-icon">✅</div>
        <h1>Merci pour votre achat !</h1>
        <p>Votre commande a bien été enregistrée.</p>
        <p>Vous allez être redirigé vers l'accueil dans <span id="countdown" class="countdown">5</span> secondes...</p>
        <!-- Remontez d'un niveau pour accéder à index.php -->
        <a href="../index.php" class="redirect-link">Retourner à l'accueil</a>
    </div>

    <script>
        // Compte à rebours
        let seconds = 5;
        const countdownElement = document.getElementById('countdown');
        
        const interval = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(interval);
                // Remontez d'un niveau pour accéder à index.php
                window.location.href = "../index.php";
            }
        }, 1000);
    </script>
</body>
</html>