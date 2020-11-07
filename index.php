<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <title>Explorador de Archivos</title>
    <link rel="stylesheet" type="text/css" href="https://bootswatch.com/4/darkly/bootstrap.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/style.css" />
</head>

<body>
<div class="container">
<?php
require_once "./config.php";
// obtenemos la ruta a revisar, y la ruta anterior para volver...
$back           = "";
$initialPath    = _PATH;
$path           = $initialPath;

if(isset($_GET["path"]) && $_GET["path"] != "")
{
    $path = base64_decode($_GET["path"]);
    $back = implode("/", explode("/", $path, -1));
    if($back != "")
        $back.= "";
    else
        $back = $initialPath;
}
?>
<header class="pt-3">
    <h4>Explorador de Archivos</h4>
	<hr/>
</header>
<nav>
    <div class="btn-toolbar">
        <div class="mr-2">
            <button class="btn btn-outline-success btn-sm" type="button" onclick="goHome('<?=$_SERVER['SCRIPT_NAME']?>')"><i class="fa fa-home"></i> Inicio</button>
        </div>
        <div clas="btn-group mr-2">
        <?php
            // si no estamos en la raiz, permitimos volver hacia atras
            if($path != $initialPath){
                ?><button class="btn btn-primary btn-sm" onclick="goBack('<?=base64_encode($back)?>')"><i class="fa fa-reply"></i> Atras</button><?php
            }
            else{
                ?><button type="button" class="btn btn-primary btn-sm" disabled=""><i class="fa fa-long-arrow-left"></i> Atras</button><?php
            }
        ?>
        </div>
        <div class="ml-3">
            <p class="bold"><?php echo $path?></p>
        </div>
    </div>
</nav>
<section>
<?php
    // devuelve el tipo mime de su extensión (desde PHP 5.3)
    $finfo1 = @finfo_open(FILEINFO_MIME_TYPE);
    // devuelve la codificación mime del fichero (desde PHP 5.3)
    $finfo2 = @finfo_open(FILEINFO_MIME_ENCODING);

    $folder=0;
    $file=0;
    
    ?>
    <div class="table-responsive p-3 bg-dark">
        <table class="table table-bordered table-condensed table-striped table-sm">
            <thead>
                <tr>
                    <th>Nombre de archivo</th>
                    <th>Tipo</th>
                    <th>Tama&ntilde;o</th>
                </tr>
            </thead>
            <tbody>
                <?php
                # recorremos todos los archivos de la carpeta
                foreach (glob($path.'/*') as $fullDirFileName)
                {
                    $fileMime=@finfo_file($finfo1, $fullDirFileName);
                    $fileEncoding=@finfo_file($finfo2, $fullDirFileName);
                    $fileName = explode("/",$fullDirFileName);
                    ?>
                    <tr>
                    <?php
                    if($fileMime=="directory")
                    {
                        $folder+=1;
                        ?>
                            <td><a href="?path=<?=base64_encode($fullDirFileName)?>" class="name"><?=end($fileName)?></a></td>
                            <td>(<?=$fileEncoding?>)</td>
                            <td></td>
                        <?php
                    }else{
                        $file+=1;
                        ?>
                        <td><?=end($fileName)?></td>
                        <td><?=$fileMime." (".$fileEncoding.")"?></td>
                        <td class="text-right"><?=number_format(filesize($fullDirFileName)/1024,2,",",".")?> Kb</td>
                        <?php
                    }
                    ?>
                    </tr>
                    <?php
                }
                if($folder == 0 && $file == 0){
                    ?>
                    <tr>
                        <td colspan="3" class="text-center">Esta carpeta est&aacute; vac&iacute;a</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
			<tfoot>
				<tr>
					<td colspan="3" class="text-center">
						<?=$folder?> carpeta/s y <?=$file?> archivo/s
					</td>
				</tr>
			</tfoot>
        </table>
    </div>
    <?php

    finfo_close($finfo1);
    finfo_close($finfo2);
    ?>
</section>
</div>
<script src="./js/index.js" type="text/javascript"></script>
</body>
</html>