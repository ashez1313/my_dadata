<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$placement = $_REQUEST['PLACEMENT'];
$placementOptions = isset($_REQUEST['PLACEMENT_OPTIONS']) ? json_decode($_REQUEST['PLACEMENT_OPTIONS'], true) : array();
$handler = ($_SERVER['SERVER_PORT'] === '443' ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];

if (!is_array($placementOptions)) {
	$placementOptions = array();
}

if ($placement === 'DEFAULT') {
	$placementOptions['MODE'] = 'edit';
}
?>
<!DOCTYPE html>
<html>

<head>
	<script src="//api.bitrix24.com/api/v1/dev/"></script>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link href="http://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css" rel="stylesheet" />
	<style>
		.btn-secondary {
			width: 30%;
		}

		.placement {
			text-align: center;
		}
	</style>
</head>

<body
	style="margin: 0; padding: 0; text-align: center; background-color: <?= $placementOptions['MODE'] === 'edit' ? '#fff' : '#f9fafb' ?>;">
	<div class="workarea" style="width: 100%; height: 100%">
		<?
		if ($placement === 'DEFAULT'):
			?>
			<img style="text-aligh: right;" src="img/logo2.jpg">
			<p><a class="btn btn-secondary" href="javascript:void(0)" onclick="test.add()">Добавить
					пользовательское поле MуDadata</a></p>
			<p><a class="btn btn-secondary" href="javascript:void(0)" onclick="test.list()">Список
					добавленных полей</a></p>
			<p><a class="btn btn-secondary" href="javascript:void(0)" onclick="test.update()">Обновить
					пользовательское поле</a></p>
			<p><a class="btn btn-secondary" href="javascript:void(0)" onclick="test.del()">Удалить
					пользовательское поле</a></p>

			<pre id="debug" style="border: solid 1px #aaa; padding: 10px; background-color: #eee">&nbsp;</pre>
			<script>

				var test = {
					call: function (method, param) {
						BX24.callMethod(method, param, test.debug);
					},

					debug: function (result) {
						var s = '';
						s += '<b>' + result.query.method + '</b>\n';
						s += JSON.stringify(result.query.data, null, '  ') + '\n\n';
						if (result.error()) {
							s += '<span style="color: red">Error! ' + result.error().getStatus() + ': ' + result.error().toString() + '</span>\n';
						}
						else {
							s += '<span>' + JSON.stringify(result.data(), null, '  ') + '</span>\n';
						}
						document.getElementById('debug').innerHTML = s;
					},

					add: function () {
						test.call('userfieldtype.add', {
							USER_TYPE_ID: 'mydadata',
							HANDLER: '<?= $handler ?>',
							TITLE: 'MyDadata',
							DESCRIPTION: 'MyDadata'
						});
					},

					list: function () {
						test.call('userfieldtype.list', {});
					},

					update: function () {
						test.call('userfieldtype.update', {
							TITLE: 'MyDadata',
							USER_TYPE_ID: 'mydadata',
							DESCRIPTION: 'MyDadata. Updated on ' + (new Date()).toString()
						});
					},

					del: function () {
						test.call('userfieldtype.delete', {
							USER_TYPE_ID: 'mydadata'
						});
					}
				}
			</script>
			<?
		elseif ($placement === 'USERFIELD_TYPE'):
			if ($placementOptions['MODE'] === 'edit') {
				if ($placementOptions['MULTIPLE'] === 'N') {
					?>
					<div style="height: 170px">
						<input id="address" class="placement" name="address" type="text" class="form-control"
							style="width: 90%; height: 50px;" value="<?= htmlspecialchars($placementOptions['VALUE']) ?>"
							onkeyup="setValue(this.value)">
					</div>

					<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
					<script
						src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js"></script>

					<script src="js/suggest.js"></script>

					<script>
						function setValue(value) {
							BX24.placement.call('setValue', value);
						}
					</script>
					<?
				} else {
					?>
					<textarea style="width: 90%; height: 100%;"
						onkeyup="setValue(this.value)"><?= htmlspecialchars(implode("\n", $placementOptions['VALUE'])) ?></textarea>
					<script>
						function setValue(value) {
							BX24.placement.call('setValue', value.split('\n'));
						}
					</script>
					<?
				}
			} else {
				echo htmlspecialchars($placementOptions['VALUE']);
			}

		endif;
		?>
	</div>
	<script>
		BX24.ready(function () {
			BX24.init(function () {
				BX24.resizeWindow(document.body.clientWidth,
					document.getElementsByClassName("workarea")[0].clientHeight);
			})
		});
	</script>

</body>
</html>
