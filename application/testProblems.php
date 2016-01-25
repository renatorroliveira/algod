<?php
	if (!isset($_GET['tpb_tst_id']))
		die("<b>Forbidden.</b>");
	$__REQUIRE_ACCESS_LEVEL__ = 3;
	$__REDIRECT_TO__ = './';
	include "static/header.php";
?>

<h1>Composição da Prova<br />Gerenciar Problemas/Questões</h1>

<p>
	Neste tela são exibidos os problemas (questões) que compõem esta prova. Aqui você pode incluir problemas,
	excluir existentes, editar existentes e organizar a ordem e o peso das questões. Esses problemas serão
	exportados no código C++ caso você gere de uma prova específica. Os problemas podem ser exportados como um
	bundle apenas, contendo todo o banco de dados do sistema.
</p>

<button id="testproblems-bt-new">
	Anexar Problema
</button>

<div id="testproblems-ct-list" class="centering">
	<?php
		include "view/TestSelect.php";
		$filter = new TestSelect();
		$selectedTest = $_GET['tpb_tst_id'];
		?><br /><div><label for="tpb_tst_id">Filtrar por Prova</label>:<?php
		$filter->render("tpb_tst_id", $selectedTest, null, 'test-filter');
		?></div><?php
		
		include "view/TestProblemsList.php";
		$wdg = new TestProblemsList();
		$wdg->renderNotDeleted($selectedTest);
	?>
</div>

<div id="testproblems-new-form" style="display:none;">
	<?php
		include "./view/TestProblemsEdit.php";
		$widget = new TestProblemsEdit(null);
		$widget->render($selectedTest);
	?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#testproblems-bt-new").click(function() {
			$("#testproblems-new-form").dialog({
				modal: true,
				width: 'auto',
				height: 'auto',
				title: 'Anexar um Problema à Prova',
				buttons: {
					Cancel: function() {
						$(this).dialog("close");
					}
				}
			}).dialog("open");
		});

		$("#test-filter").change(function() {
			var initial = "<?php echo $selectedTest; ?>";
			if (($(this).val() != "") && ($(this).val() != initial))
				location.assign("./testProblems.php?tpb_tst_id="+$(this).val());
		});
                
                (function( $ ) {
                        $.widget( "custom.combobox", {
                                _create: function() {
                                        this.wrapper = $( "<span>" )
                                        .addClass( "custom-combobox" )
                                        .insertAfter( this.element );
                                        this.element.hide();
                                        this._createAutocomplete();
                                        this._createShowAllButton();
                                },
                                _createAutocomplete: function() {
                                        var selected = this.element.children( ":selected" ),
                                        value = selected.val() ? selected.text() : "";
                                        this.input = $( "<input>" )
                                        .appendTo( this.wrapper )
                                        .val( value )
                                        .attr( "title", "" )
                                        .attr( "type", "text")
                                        .attr( "size", "60")
                                        .addClass( "notempty" )
                                        .autocomplete({
                                                delay: 0,
                                                minLength: 0,
                                                source: $.proxy( this, "_source" )
                                        })
                                        .tooltip({
                                                tooltipClass: "ui-state-highlight"
                                        });
                                        this._on( this.input, {
                                                autocompleteselect: function( event, ui ) {
                                                        ui.item.option.selected = true;
                                                        this._trigger( "select", event, {
                                                                item: ui.item.option
                                                        });
                                                },
                                                autocompletechange: "_removeIfInvalid"
                                        });
                                },
                                _createShowAllButton: function() {
                                        var input = this.input,
                                        wasOpen = false;
                                        $( "<a>" )
                                        .attr( "tabIndex", -1 )
                                        .attr( "title", "Mostrar todos os itens" )
                                        .tooltip()
                                        .appendTo( this.wrapper )
                                        .button({
                                                icons: {
                                                        primary: "ui-icon-triangle-1-s"
                                                },
                                                text: false
                                        })
                                        .removeClass( "ui-corner-all" )
                                        .addClass( "custom-combobox-toggle ui-corner-right" )
                                        .mousedown(function() {
                                                wasOpen = input.autocomplete( "widget" ).is( ":visible" );
                                        })
                                        .click(function() {
                                                input.focus();
                                                // Close if already visible
                                                if ( wasOpen ) {
                                                        return;
                                                }
                                                // Pass empty string as value to search for, displaying all results
                                                input.autocomplete( "search", "" );
                                        });
                                },
                                _source: function( request, response ) {
                                        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                                        response( this.element.children( "option" ).map(function() {
                                                var text = $( this ).text();
                                                if ( this.value && ( !request.term || matcher.test(text) ) )
                                                return {
                                                        label: text,
                                                        value: text,
                                                        option: this
                                                };
                                        }) );
                                },
                                _removeIfInvalid: function( event, ui ) {
                                        // Selected an item, nothing to do
                                        if ( ui.item ) {
                                                return;
                                        }
                                        // Search for a match (case-insensitive)
                                        var value = this.input.val(),
                                        valueLowerCase = value.toLowerCase(),
                                        valid = false;
                                        this.element.children( "option" ).each(function() {
                                                if ( $( this ).text().toLowerCase() === valueLowerCase ) {
                                                        this.selected = valid = true;
                                                        return false;
                                                }
                                        });
                                        // Found a match, nothing to do
                                        if ( valid ) {
                                                return;
                                        }
                                        // Remove invalid value
                                        this.input
                                        .val( "" )
                                        .attr( "title", value + " nenhum item encontrado" )
                                        .tooltip( "open" );
                                        this.element.val( "" );
                                        this._delay(function() {
                                                this.input.tooltip( "close" ).attr( "title", "" );
                                        }, 2500 );
                                        this.input.data( "ui-autocomplete" ).term = "";
                                },
                                _destroy: function() {
                                        this.wrapper.remove();
                                        this.element.show();
                                }
                        });
                })( jQuery );

                $(function() {
                    $( "#problems-select" ).combobox();
                });
                
	});
</script>

<?php
	include "static/footer.php";
?>