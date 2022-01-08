<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../CSS/style.css">
        <link rel="stylesheet" type="text/css" href="../CSS/styleChart.css">
        <title>These</title>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/series-label.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="http://code.highcharts.com/maps/modules/map.js"></script>
        <script src="https://code.highcharts.com/mapdata/countries/fr/fr-all.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </head>
        <header>
            <a href="https://github.com/Rwouaf/Dev_Web_Projet"><img src="../images/github.png" width="75px" style="margin-left: 1rem"></a>
        </header>

        <div class="container">
            <div class="left">
                <img src="../images/theseLogo.png" class="logo">
                    <form method="GET" class="searchBar">
                        <input type="search" class="search" name="keyWord" placeholder="Recherche..." autofocus required/>
                        <button type="submit" class="searchResult" value="Valider"> GO </button>
                    </form>

                <?php
                require_once("connexion.php");
                require_once ("../class/these.php");

                $cnx = new connexion();
                $db = $cnx ->getCnx();
                $keywordSQL = "";
                $articles = $db->prepare('SELECT id_these FROM `these`');
                if(isset($_GET['keyWord']) AND !empty($_GET['keyWord'])) {
                    $keywordSQL = "%".$_GET['keyWord']."%";
                    $keyword = $_GET['keyWord'];
                    $articles = $db->prepare('SELECT id_these FROM these WHERE titre LIKE :keyWord OR auteur LIKE :keyWord ORDER BY auteur DESC');
                    $articles->bindParam('keyWord',$keywordSQL,PDO::PARAM_STR,500);
                    $articles->execute();
                }

                $dicipline = null;
                if (isset($_GET['dicipline'])){
                    $dicipline = "%".$_GET['dicipline']."%";
                }

                if(isset($_GET['keyWord']) AND !empty($_GET['keyWord']) AND  $dicipline != null){
                    $articles = $db->prepare('SELECT id_these FROM these WHERE (titre LIKE :keyWord OR auteur LIKE :keyWord) AND dicipline like :dicipline ORDER BY auteur DESC');
                    $articles->bindParam('keyWord',$keywordSQL,PDO::PARAM_STR,500);
                    $articles->bindParam('dicipline',$dicipline,PDO::PARAM_STR,100);
                    $articles->execute();
                }

                $chart = 1;
                if (!empty($_GET['chart'])){
                    $chart = $_GET['chart'];
                }

                $result = 0;
                $these = null;
                     if($articles->rowCount() > 0) {
                         $result = 1;
                        echo '<div class = "nbResult"> Nombre de résultat pour ' . $_GET['keyWord'] . ': ' . $articles->rowCount().'</div>';
                        ?>
                        <div class="result">
                            <ul>
                                <?php
                                $tab = array();
                                while($resultat = $articles->fetch()) {
                                    $id = $resultat['id_these'];
                                    $reflection = new ReflectionClass("these");
                                    $these = $reflection->newInstanceWithoutConstructor();
                                    $these->setIdThese($id);
                                    /* @var $these these */
                                    $these->load();
                                    $tab[] = $these;
                                    ?>
                                    <li>
                                        <?php
                                        echo "<a href='https://theses.fr/". $these->getIdThese()."target='_blank'>". $these->getTitre()."</a><hr> "
                                        ?>
                                    </li>
                                <?php }
                                ?>
                            </ul>
                            <?php } else { ?>
                                    <div class="result">
                                        Aucun résultat pour: <?= $keywordSQL ?>...
                                    </div>
                            <?php } ?>
                        </div>
            </div>
            <?php
            //requete pour chart nbthese/années
            $date = $db->query('SELECT MIN(year(publication)) min, MAX(year(publication)) max FROM these ORDER BY publication');
            $date = $date->fetch();
            $res = $db->query('SELECT year(publication) y, COUNT(id_these) nbthese FROM these where year(publication) > 0 GROUP BY year(publication) ORDER BY y');
            $countyear = array();
            while ($obj = $res->fetchObject()) {
                $countyear[] = $obj->nbthese;
            }


            //requete dicipline
            $dicipline = $db->prepare('SELECT dicipline, COUNT(dicipline) nbthese FROM these WHERE titre LIKE :keyWord GROUP BY dicipline ORDER BY nbthese DESC LIMIT 10');
            $dicipline->bindParam('keyWord',$keywordSQL, PDO::PARAM_STR,500);
            $dicipline->execute();
            $countdicipline = array();
            while ($obj = $dicipline->fetchObject()) {
                $countdicipline[$obj->dicipline] = $obj->nbthese;
            }

            //requete etab region
            $etab = $db->prepare('SELECT COUNT(etablissement) as nbetab, idRegion FROM `localisation` as l, these as t WHERE l.etablissement = t.id_etab AND titre like :keyWord GROUP by idRegion;');
            $etab->bindParam('keyWord',$keywordSQL, PDO::PARAM_STR,500);
            $etab->execute();
            $countEtab = array();
            while ($obj = $etab->fetchObject()) {
                $countEtab[$obj->idRegion] = $obj->nbetab;
            }


            if ($result != 0){ ?>

            <div class="right">
            <?php
                if ($chart == 1){
                ?>
                <figure class="highcharts-figure">
                    <div id="chartPie" class="chart pie"></div>
                </figure>
                <script type="text/javascript">
                            Highcharts.chart('chartPie', {
                                chart: {
                                    plotBackgroundColor: null,
                                    plotBorderWidth: null,
                                    plotShadow: false,
                                    type: 'pie'
                                },
                                title: {
                                    text: 'Top 10 pourcentage discipline pour la recherche : <?= $_GET['keyWord'] ?>'
                                },
                                tooltip: {
                                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                },
                                accessibility: {
                                    point: {
                                        valueSuffix: '%'
                                    }
                                },
                                plotOptions: {
                                    series: {
                                        cursor: 'pointer',
                                        point: {
                                            events: {
                                                click: function () {
                                                    location.href = 'index.php?keyWord=Conception&dicipline=' +
                                                        this.options.name;
                                                }
                                            }
                                        }
                                    }
                                },
                                series: [{
                                    name: 'pourcentage',
                                    colorByPoint: true,
                                    data: [
                                        <?php
                                        foreach ($countdicipline as $discipline=>$nbdisc ){
                                            echo "{";
                                            echo "name: '".addslashes($discipline)."',";
                                            echo "y: ".$nbdisc.",";
                                            echo "},";
                                        }
                                        ?>
                                       ]
                                }]
                            });

                        </script>
                <?php } elseif ($chart == 3){
                ?>
                    <figure class="highcharts-figure">
                        <div id="chart" class="chart pie"></div>
                    </figure>

                    <script type="text/javascript">
                            const data = [
                            ['fr-cor', <?= $countEtab['R94'] ?? 0 ?>],
                            ['fr-bre', <?= $countEtab['R53'] ?? 0 ?>],
                            ['fr-pdl', <?= $countEtab['R52'] ?? 0 ?>],
                            ['fr-pac', <?= $countEtab['R93'] ?? 0 ?>],
                            ['fr-occ', <?= $countEtab['R76'] ?? 0 ?>],
                            ['fr-naq', <?= $countEtab['R75'] ?? 0 ?>],
                            ['fr-bfc', <?= $countEtab['R27'] ?? 0 ?>],
                            ['fr-cvl', <?= $countEtab['R24'] ?? 0 ?>],
                            ['fr-idf', <?= $countEtab['R11'] ?? 0 ?>],
                            ['fr-hdf', <?= $countEtab['R32'] ?? 0 ?>],
                            ['fr-ara', <?= $countEtab['R84'] ?? 0 ?>],
                            ['fr-ges', <?= $countEtab['R44'] ?? 0 ?>],
                            ['fr-nor', <?= $countEtab['R28'] ?? 0 ?>],
                            ['fr-lre', <?= $countEtab['R04'] ?? 0 ?>],
                            ['fr-may', <?= $countEtab['R06'] ?? 0 ?>],
                            ['fr-gf', <?= $countEtab['R03'] ?? 0 ?>],
                            ['fr-mq', <?= $countEtab['R00'] ?? 0 ?>],
                            ['fr-gua', <?= $countEtab['R01'] ?? 0 ?>]
                            ];

                            // Create the chart
                            Highcharts.mapChart('chart', {
                                chart: {
                                    map: 'countries/fr/fr-all'
                                },

                                title: {
                                    text: 'Highmaps basic demo'
                                },

                                subtitle: {
                                    text: 'Source map: <a href=\'http://code.highcharts.com/mapdata/countries/fr/fr-all.js\'>France</a>'
                                },

                                mapNavigation: {
                                    enabled: false,
                                    buttonOptions: {
                                        verticalAlign: 'bottom'
                                    }
                                },

                                colorAxis: {
                                    min: 0
                                },

                                series: [{
                                    data: data,
                                    name: 'Nombre de thèses',
                                    states: {
                                        hover: {
                                            color: '#BADA55'
                                        }
                                    },
                                    dataLabels: {
                                        enabled: true,
                                        format: '{point.name}'
                                    }
                                }, {
                                    name: 'Separators',
                                    type: 'mapline',
                                    data: Highcharts.geojson(Highcharts.maps['countries/fr/fr-all'], 'mapline'),
                                    color: 'silver',
                                    nullColor: 'silver',
                                    showInLegend: false,
                                    enableMouseTracking: false
                                }]
                            });

                    </script>
                <?php } ?>


                <div class="navigation">
                    <ul>
                        <li class="list active">
                            <a href="index.php?keyWord=<?=$keyword?>&chart=1">
                                <span class="icon"><ion-icon name="pie-chart-outline"></ion-icon></span>
                                <span class="text">Pie</span>
                            </a>
                        </li>
                        <li class="list">
                            <a href="index.php?keyWord=<?=$keyword?>&chart=3">
                                <span class="icon"><ion-icon name="earth-sharp"></ion-icon></span>
                                <span class="text">Map</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
                <?php }
                    else {
                ?>
            <div class="right">
                <figure class="highcharts-figure">
                    <div id="chart" class="chart curve" ></div>
                </figure>
                <script type="text/javascript">
                            Highcharts.chart('chart', {

                                title: {
                                    text: 'Nombre de thèses en fonction des années, <?= $date['min'].'-'.$date['max'] ?>'
                                },

                                subtitle: {
                                    text: 'Source: thesesfr.com'
                                },

                                yAxis: {
                                    title: {
                                        text: 'Nombre de thèses'
                                    }
                                },

                                xAxis: {
                                    accessibility: {
                                        rangeDescription: 'Range: <?= $date['min']?> to <?= $date['max'] ?>'
                                    }
                                },

                                legend: {
                                    layout: 'vertical',
                                    align: 'right',
                                    verticalAlign: 'middle'
                                },

                                plotOptions: {
                                    series: {
                                        label: {
                                            connectorAllowed: false
                                        },
                                        pointStart: <?= $date['min']?>
                                    }
                                },

                                series: [{
                                    name: 'Nombre thèses',
                                    data: [<?= implode(',',$countyear) ?>]
                                }],

                                responsive: {
                                    rules: [{
                                        condition: {
                                            maxWidth: 500
                                        },
                                        chartOptions: {
                                            legend: {
                                                layout: 'horizontal',
                                                align: 'center',
                                                verticalAlign: 'bottom'
                                            }
                                        }
                                    }]
                                }

                            });
                        </script>
            </div>
            <?php }
            ?>
        </div>
    </body>
</html>

