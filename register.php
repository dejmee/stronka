<?php
/**
 * Skrypt i formularz rejestracji
 * @author Sobak
 * @package User System
 */

require 'header.php'; // Do��cz pocz�tkowy kod HTML
require 'config.php'; // Do��cz plik konfiguracyjny i po��czenie z baz�
require_once 'user.class.php';

/**
 * Sprawd� czy formularz zosta� wys�any
 */
if ($_POST['send'] == 1) {
    // Zabezpiecz dane z formularza przed kodem HTML i ewentualnymi atakami SQL Injection
    $login = mysql_real_escape_string(htmlspecialchars($_POST['login']));
    $pass = mysql_real_escape_string(htmlspecialchars($_POST['pass']));
    $pass_v = mysql_real_escape_string(htmlspecialchars($_POST['pass_v']));
    $email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
    $email_v = mysql_real_escape_string(htmlspecialchars($_POST['email_v']));

    /**
     * Sprawd� czy podany przez u�ytkownika email lub login ju� istnieje
     */
    $existsLogin = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM users WHERE login='$login' LIMIT 1"));
    $existsEmail = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM users WHERE email='$email' LIMIT 1"));

    $errors = ''; // Zmienna przechowuj�ca list� b��d�w kt�re wyst�pi�y


    // Sprawd�, czy nie wyst�pi�y b��dy
    if (!$login || !$email || !$pass || !$pass_v || !$email_v ) $errors .= '- Musisz wype�ni� wszystkie pola<br />';
    if ($existsLogin[0] >= 1) $errors .= '- Ten login jest zaj�ty<br />';
    if ($existsEmail[0] >= 1) $errors .= '- Ten e-mail jest ju� u�ywany<br />';
    if ($email != $email_v) $errors .= '- E-maile si� nie zgadzaj�<br />';
    if ($pass != $pass_v)  $errors .= '- Has�a si� nie zgadzaj�<br />';

    /**
     * Je�li wyst�pi�y jakie� b��dy, to je poka�
     */
    if ($errors != '') {
        echo '<p class="error">Rejestracja nie powiod�a si�, popraw nast�puj�ce b��dy:<br />'.$errors.'</p>';
    }

    /**
     * Je�li nie ma �adnych b��d�w - kontynuuj rejestracj�
     */
    else {

        // Pos�l i zasahuj has�o
        $pass = user::passSalter($pass);

        // Zapisz dane do bazy
        mysql_query("INSERT INTO users (login, email, pass) VALUES('$login','$email','$pass');") or die ('<p class="error">Wyst�pi� b��d w zapytaniu i nie uda�o si� zarejestrowa� u�ytkownika.</p>');

        echo '<p class="success">'.$login.', zosta�e� zarejestrowany.
        <br /><a href="login.php">Logowanie</a></p>';
    }
}
?>

<form method="post" action="">
 <label for="login">Login:</label>
 <input maxlength="32" type="text" name="login" id="login" />

 <label for="pass">Has�o:</label>
 <input maxlength="32" type="password" name="pass" id="pass" />

 <label for="pass_again">Has�o (ponownie):</label>
 <input maxlength="32" type="password" name="pass_v" id="pass_again" />

 <label for="email">E-mail:</label>
 <input type="text" name="email" maxlength="50" id="email" />

 <label for="email_again">E-mail (ponownie):</label>
 <input type="text" maxlength="255" name="email_v" id="email_again" /><br />


 <input type="hidden" name="send" value="1" />
 <input type="submit" value="Zarejestruj" />
</form>

<?php
require 'footer.php'; // Do��cz ko�cowy kod HTML
?>