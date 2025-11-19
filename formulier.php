<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="container">
            <div id="header">
                <h1>Donatie Formulier</h1>
            </div>
            <div id="content">
                <form action="formulier.php" method="post">
                    Type:<br/>
                    <select name="type">
                    <option value="1 ">Laptop</option>
                    <option value="2 ">Robots</option>
                    <option value="3 ">3D-printers</option>
                    </select><br/><br/>
                    Aantal:<br/> <input type="number" name="aantal"><br/><br/>
                    Staat:<br/>
                    <select name="staat">
                    <option value="Nieuw">Nieuw</option>
                    <option value="Gebruikt">Gebruikt</option>
                    </select><br/><br/>
                    Postcode:<br/> <input type="text" name="postcode"><br/><br/>
                    <input type="submit" name="submit" value="Doneer">
                </form>

            </div>
        </div>
    </body>
</html>