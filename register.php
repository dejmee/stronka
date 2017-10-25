<?php
/**
 * Skrypt i formularz rejestracji
 * @author Sobak
 * @package User System
 */

require 'header.php'; // Do³¹cz pocz¹tkowy kod HTML
require 'config.php'; // Do³¹cz plik konfiguracyjny i po³¹czenie z baz¹
require_once 'user.class.php';

/**
 * SprawdŸ czy formularz zosta³ wys³any
 */
if ($_POST['send'] == 1) {
    // Zabezpiecz dane z formularza przed kodem HTML i ewentualnymi atakami SQL Injection
    $login = mysql_real_escape_string(htmlspecialchars($_POST['login']));
    $pass = mysql_real_escape_string(htmlspecialchars($_POST['pass']));
    $pass_v = mysql_real_escape_string(htmlspecialchars($_POST['pass_v']));
    $email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
    $email_v = mysql_real_escape_string(htmlspecialchars($_POST['email_v']));

    /**
     * SprawdŸ czy podany przez u¿ytkownika email lub login ju¿ istnieje
     */
    $existsLogin = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM users WHERE login='$login' LIMIT 1"));
    $existsEmail = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM users WHERE email='$email' LIMIT 1"));

    $errors = ''; // Zmienna przechowuj¹ca listê b³êdów które wyst¹pi³y


    // SprawdŸ, czy nie wyst¹pi³y b³êdy
    if (!$login || !$email || !$pass || !$pass_v || !$email_v ) $errors .= '- Musisz wype³niæ wszystkie pola<br />';
    if ($existsLogin[0] >= 1) $errors .= '- Ten login jest zajêty<br />';
    if ($existsEmail[0] >= 1) $errors .= '- Ten e-mail jest ju¿ u¿ywany<br />';
    if ($email != $email_v) $errors .= '- E-maile siê nie zgadzaj¹<br />';
    if ($pass != $pass_v)  $errors .= '- Has³a siê nie zgadzaj¹<br />';

    /**
     * Jeœli wyst¹pi³y jakieœ b³êdy, to je poka¿
     */
    if ($errors != '') {
        echo '<p class="error">Rejestracja nie powiod³a siê, popraw nastêpuj¹ce b³êdy:<br />'.$errors.'</p>';
    }

    /**
     * Jeœli nie ma ¿adnych b³êdów - kontynuuj rejestracjê
     */
    else {

        // Posól i zasahuj has³o
        $pass = user::passSalter($pass);

        // Zapisz dane do bazy
        mysql_query("INSERT INTO users (login, email, pass) VALUES('$login','$email','$pass');") or die ('<p class="error">Wyst¹pi³ b³¹d w zapytaniu i nie uda³o siê zarejestrowaæ u¿ytkownika.</p>');

        echo '<p class="success">'.$login.', zosta³eœ zarejestrowany.
        <br /><a href="login.php">Logowanie</a></p>';
    }
}
?>

<form method="post" action="">
 <label for="login">Login:</label>
 <input maxlength="32" type="text" name="login" id="login" />

 <label for="pass">Has³o:</label>
 <input maxlength="32" type="password" name="pass" id="pass" />

 <label for="pass_again">Has³o (ponownie):</label>
 <input maxlength="32" type="password" name="pass_v" id="pass_again" />

 <label for="email">E-mail:</label>
 <input type="text" name="email" maxlength="50" id="email" />

 <label for="email_again">E-mail (ponownie):</label>
 <input type="text" maxlength="255" name="email_v" id="email_again" /><br />


 <input type="hidden" name="send" value="1" />
 <input type="submit" value="Zarejestruj" />
</form>

<?php
require 'footer.php'; // Do³¹cz koñcowy kod HTML
?>