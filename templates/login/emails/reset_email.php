<?php
/*
Subject: Réinitialisez votre mot de passe
Description: Send reset email to user
*/
?>
Bonjour <?= $data['user']['user_name'] ?>,
<br />  
<br />Vous avez demandé à réinitialiser votre mot de passe sur l\'application Certifopac.
<br />
<br />Vous pouvez le modifier en cliquant sur le lien ci-dessous : 
<br /><br />
<a href="<?= $data['resetLink'] ?>"><?= $data['resetLink'] ?></a>
<br><br>
Cordialement,
<br>
L’équipe Certifopac
<br><br>
<i>Ce courriel est adressé à partir d’une boite mail automatique, merci de ne pas y répondre. En cas de question, merci de privilégier l’adresse mail <a href="mailto:audit@certifopac.fr">audit@certifopac.fr</a></i>
<br />
<br /><i>Ceci n’est pas un e-mail marketing, vous recevez ce message car vous disposez d’un contrat valide et que nous sommes tenus par le règlement et le programme de certification de vous informer à tout moment du cycle. Si vous ne souhaitez plus recevoir ces e-mails, merci de nous en informer à l’adresse dpo@certifopac.fr.</i>