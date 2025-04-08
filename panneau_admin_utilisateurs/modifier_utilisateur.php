<?php $id = $_GET['id'] ?? null; ?>
<h1>Modifier utilisateur ID <?= $id ?></h1>
<form method="post" action="#">
    <input type="text" name="nom" value="Nom actuel"><br><br>
    <input type="email" name="email" value="email@example.com"><br><br>
    <select name="role">
        <option value="admin">Admin</option>
        <option value="utilisateur">Utilisateur</option>
    </select><br><br>
    <button type="submit">Mettre à jour</button>
</form>
<a href="utilisateurs.php">⬅ Retour</a>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }
    h1 {
        color: #333;
    }
    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    input[type="text"], input[type="email"], select {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    button {
        background-color:rgb(56, 235, 98);
        color: white;
        padding: 10px;
        border-radius: 5px;
        border: none;
    }
    button:hover {
        background-color:rgb(41, 247, 85);
    }
    a {
        display: inline-block;
        margin-top: 10px;
        text-decoration: none;
        color:rgb(0, 255, 179);
    }
    a:hover {
        text-decoration: underline;
    }
    .ajouter-annonce button {
        background-color:rgb(0, 255, 42);
        color: white;
        border: none;
        cursor: pointer;
    }
    .ajouter-annonce button:hover {
        background-color:rgb(0, 179, 45);
    }
</style>