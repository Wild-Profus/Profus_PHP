<section>
	<!-- On met les fichiers javascript en fin de document pour accélérer le chargement de la page-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<!-- Jquery UI (autocomplete)-->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<!-- Charger jquery avant boostrap est important -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<!-- Activation des tooltips une fois que la page est chargée -->
	<script>
	$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip({
                trigger : 'hover'
            });
            $('[data-toggle="popover"]').popover();
		});
	</script>
	<!-- Boutons Toggle de Bootsrap toggle -->
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<!-- Pour la pagination des tableaux-->
	<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="../js/navbar.js"> </script>
</section>