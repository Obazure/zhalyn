<h1 class="align-center">Заказать обед</h1>
<form method="post" action="<?=$data['formpath']?>">

<?
	if($data['loginstatus']==true){
		?>
		<div class="field align-center">
			<label for="name">Добро пожаловать.</label>
		</div>
		<?
	}else{
?>
<div class="field align-center">
	<label for="name">Подскажите как нам узнать вас?</label>
</div>
<div class="field half">
	<input type="text" name="name" required	 placeholder="Имя" />
</div>
<div class="field half">
	<input type="password" name="password" required	 placeholder="Секретное слово" />
</div>
<? } ?>
<div class="field align-center">
	<input type="submit" value="Выбрать блюда" class="special" /></li>
</div>	

</form>	

	<?
	if($data['loginstatus']==false){ 
	?>
<h4>Примечание:</h4>
<p>Если вы впервые на портале, укажите ваше имя,<br/>
чтобы мы знали как к вам обращаться и<br/>
секретное слово, которое будете знать только вы.</p>
	<?
	}
	?>
	
