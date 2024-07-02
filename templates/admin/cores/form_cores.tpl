{include file="page_header.tpl"}
<div class="lineForm">
	<div class="tituloLateral">
		Colors
	</div>
	<div class="formLateral">
		<form id="colorForm" action="javascript: wColorFormAction()"><div>
			<select id="elementSelector">
{section name=opt loop=$elementosColoridos}
{if $elementosColoridos[opt].descricaoElemento != ''}
				<option value="{math equation="x-1" x=$elementosColoridos[opt].idElementoColorido}">{$elementosColoridos[opt].descricaoElemento}</option>
{/if}
{/section}
			</select>
			<select id="zzSelectColorForm">
<script type="text/javascript">
	var rgbColor = RGBColor('');
	var cores = RGBColor.getNamedColors();
		document.writeln('<option value="">Transparente</option>');
	for (var nomeCor in cores) {
		document.writeln('<option value="' + cores[nomeCor] + '" style="background-color: #' + cores[nomeCor] + ';">' +
				nomeCor + ' </option>');
	}
/*
			<option value="AliceBlue" style="background-color: AliceBlue;">AliceBlue </option>
			<option value="AntiqueWhite" style="background-color: AntiqueWhite;">AntiqueWhite </option>
			<option value="Aqua" style="background-color: Aqua;">Aqua </option>
			<option value="Aquamarine" style="background-color: Aquamarine;">Aquamarine </option>
			<option value="Azure" style="background-color: Azure;">Azure </option>
			<option value="Beige" style="background-color: Beige;">Beige </option>
			<option value="Bisque" style="background-color: Bisque;">Bisque </option>
			<option value="Black" style="background-color: Black;">Black </option>
			<option value="BlanchedAlmond" style="background-color: BlanchedAlmond;">BlanchedAlmond </option>
			<option value="Blue" style="background-color: Blue;">Blue </option>
			<option value="BlueViolet" style="background-color: BlueViolet;">BlueViolet </option>
			<option value="Brown" style="background-color: Brown;">Brown </option>
			<option value="BurlyWood" style="background-color: BurlyWood;">BurlyWood </option>
			<option value="CadetBlue" style="background-color: CadetBlue;">CadetBlue </option>
			<option value="Chartreuse" style="background-color: Chartreuse;">Chartreuse </option>
			<option value="Chocolate" style="background-color: Chocolate;">Chocolate </option>
			<option value="Coral" style="background-color: Coral;">Coral </option>
			<option value="CornflowerBlue" style="background-color: CornflowerBlue;">CornflowerBlue </option>
			<option value="Cornsilk" style="background-color: Cornsilk;">Cornsilk </option>
			<option value="Crimson" style="background-color: Crimson;">Crimson </option>
			<option value="Cyan" style="background-color: Cyan;">Cyan </option>
			<option value="DarkBlue" style="background-color: DarkBlue;">DarkBlue </option>
			<option value="DarkCyan" style="background-color: DarkCyan;">DarkCyan </option>
			<option value="DarkGoldenRod" style="background-color: DarkGoldenRod;">DarkGoldenRod </option>
			<option value="DarkGray" style="background-color: DarkGray;">DarkGray </option>
			<option value="DarkGreen" style="background-color: DarkGreen;">DarkGreen </option>
			<option value="DarkKhaki" style="background-color: DarkKhaki;">DarkKhaki </option>
			<option value="DarkMagenta" style="background-color: DarkMagenta;">DarkMagenta </option>
			<option value="DarkOliveGreen" style="background-color: DarkOliveGreen;">DarkOliveGreen </option>
			<option value="Darkorange" style="background-color: Darkorange;">Darkorange </option>
			<option value="DarkOrchid" style="background-color: DarkOrchid;">DarkOrchid </option>
			<option value="DarkRed" style="background-color: DarkRed;">DarkRed </option>
			<option value="DarkSalmon" style="background-color: DarkSalmon;">DarkSalmon </option>
			<option value="DarkSeaGreen" style="background-color: DarkSeaGreen;">DarkSeaGreen </option>
			<option value="DarkSlateBlue" style="background-color: DarkSlateBlue;">DarkSlateBlue </option>
			<option value="DarkSlateGray" style="background-color: DarkSlateGray;">DarkSlateGray </option>
			<option value="DarkTurquoise" style="background-color: DarkTurquoise;">DarkTurquoise </option>
			<option value="DarkViolet" style="background-color: DarkViolet;">DarkViolet </option>
			<option value="DeepPink" style="background-color: DeepPink;">DeepPink </option>
			<option value="DeepSkyBlue" style="background-color: DeepSkyBlue;">DeepSkyBlue </option>
			<option value="DimGray" style="background-color: DimGray;">DimGray </option>
			<option value="DodgerBlue" style="background-color: DodgerBlue;">DodgerBlue </option>
			<option value="FireBrick" style="background-color: FireBrick;">FireBrick </option>
			<option value="FloralWhite" style="background-color: FloralWhite;">FloralWhite </option>
			<option value="ForestGreen" style="background-color: ForestGreen;">ForestGreen </option>
			<option value="Fuchsia" style="background-color: Fuchsia;">Fuchsia </option>
			<option value="Gainsboro" style="background-color: Gainsboro;">Gainsboro </option>
			<option value="GhostWhite" style="background-color: GhostWhite;">GhostWhite </option>
			<option value="Gold" style="background-color: Gold;">Gold </option>
			<option value="GoldenRod" style="background-color: GoldenRod;">GoldenRod </option>
			<option value="Gray" style="background-color: Gray;">Gray </option>
			<option value="Green" style="background-color: Green;">Green </option>
			<option value="GreenYellow" style="background-color: GreenYellow;">GreenYellow </option>
			<option value="HoneyDew" style="background-color: HoneyDew;">HoneyDew </option>
			<option value="HotPink" style="background-color: HotPink;">HotPink </option>
			<option value="IndianRed" style="background-color: IndianRed;">IndianRed </option>
			<option value="Indigo" style="background-color: Indigo;">Indigo </option>
			<option value="Ivory" style="background-color: Ivory;">Ivory </option>
			<option value="Khaki" style="background-color: Khaki;">Khaki </option>
			<option value="Lavender" style="background-color: Lavender;">Lavender </option>
			<option value="LavenderBlush" style="background-color: LavenderBlush;">LavenderBlush </option>
			<option value="LawnGreen" style="background-color: LawnGreen;">LawnGreen </option>
			<option value="LemonChiffon" style="background-color: LemonChiffon;">LemonChiffon </option>
			<option value="LightBlue" style="background-color: LightBlue;">LightBlue </option>
			<option value="LightCoral" style="background-color: LightCoral;">LightCoral </option>
			<option value="LightCyan" style="background-color: LightCyan;">LightCyan </option>
			<option value="LightGoldenRodYellow" style="background-color: LightGoldenRodYellow;">LightGoldenRodYellow </option>
			<option value="LightGrey" style="background-color: LightGrey;">LightGrey </option>
			<option value="LightGreen" style="background-color: LightGreen;">LightGreen </option>
			<option value="LightPink" style="background-color: LightPink;">LightPink </option>
			<option value="LightSalmon" style="background-color: LightSalmon;">LightSalmon </option>
			<option value="LightSeaGreen" style="background-color: LightSeaGreen;">LightSeaGreen </option>
			<option value="LightSkyBlue" style="background-color: LightSkyBlue;">LightSkyBlue </option>
			<option value="LightSlateGray" style="background-color: LightSlateGray;">LightSlateGray </option>
			<option value="LightSteelBlue" style="background-color: LightSteelBlue;">LightSteelBlue </option>
			<option value="LightYellow" style="background-color: LightYellow;">LightYellow </option>
			<option value="Lime" style="background-color: Lime;">Lime </option>
			<option value="LimeGreen" style="background-color: LimeGreen;">LimeGreen </option>
			<option value="Linen" style="background-color: Linen;">Linen </option>
			<option value="Magenta" style="background-color: Magenta;">Magenta </option>
			<option value="Maroon" style="background-color: Maroon;">Maroon </option>
			<option value="MediumAquaMarine" style="background-color: MediumAquaMarine;">MediumAquaMarine </option>
			<option value="MediumBlue" style="background-color: MediumBlue;">MediumBlue </option>
			<option value="MediumOrchid" style="background-color: MediumOrchid;">MediumOrchid </option>
			<option value="MediumPurple" style="background-color: MediumPurple;">MediumPurple </option>
			<option value="MediumSeaGreen" style="background-color: MediumSeaGreen;">MediumSeaGreen </option>
			<option value="MediumSlateBlue" style="background-color: MediumSlateBlue;">MediumSlateBlue </option>
			<option value="MediumSpringGreen" style="background-color: MediumSpringGreen;">MediumSpringGreen </option>
			<option value="MediumTurquoise" style="background-color: MediumTurquoise;">MediumTurquoise </option>
			<option value="MediumVioletRed" style="background-color: MediumVioletRed;">MediumVioletRed </option>
			<option value="MidnightBlue" style="background-color: MidnightBlue;">MidnightBlue </option>
			<option value="MintCream" style="background-color: MintCream;">MintCream </option>
			<option value="MistyRose" style="background-color: MistyRose;">MistyRose </option>
			<option value="Moccasin" style="background-color: Moccasin;">Moccasin </option>
			<option value="NavajoWhite" style="background-color: NavajoWhite;">NavajoWhite </option>
			<option value="Navy" style="background-color: Navy;">Navy </option>
			<option value="OldLace" style="background-color: OldLace;">OldLace </option>
			<option value="Olive" style="background-color: Olive;">Olive </option>
			<option value="OliveDrab" style="background-color: OliveDrab;">OliveDrab </option>
			<option value="Orange" style="background-color: Orange;">Orange </option>
			<option value="OrangeRed" style="background-color: OrangeRed;">OrangeRed </option>
			<option value="Orchid" style="background-color: Orchid;">Orchid </option>
			<option value="PaleGoldenRod" style="background-color: PaleGoldenRod;">PaleGoldenRod </option>
			<option value="PaleGreen" style="background-color: PaleGreen;">PaleGreen </option>
			<option value="PaleTurquoise" style="background-color: PaleTurquoise;">PaleTurquoise </option>
			<option value="PaleVioletRed" style="background-color: PaleVioletRed;">PaleVioletRed </option>
			<option value="PapayaWhip" style="background-color: PapayaWhip;">PapayaWhip </option>
			<option value="PeachPuff" style="background-color: PeachPuff;">PeachPuff </option>
			<option value="Peru" style="background-color: Peru;">Peru </option>
			<option value="Pink" style="background-color: Pink;">Pink </option>
			<option value="Plum" style="background-color: Plum;">Plum </option>
			<option value="PowderBlue" style="background-color: PowderBlue;">PowderBlue </option>
			<option value="Purple" style="background-color: Purple;">Purple </option>
			<option value="Red" style="background-color: Red;">Red </option>
			<option value="RosyBrown" style="background-color: RosyBrown;">RosyBrown </option>
			<option value="RoyalBlue" style="background-color: RoyalBlue;">RoyalBlue </option>
			<option value="SaddleBrown" style="background-color: SaddleBrown;">SaddleBrown </option>
			<option value="Salmon" style="background-color: Salmon;">Salmon </option>
			<option value="SandyBrown" style="background-color: SandyBrown;">SandyBrown </option>
			<option value="SeaGreen" style="background-color: SeaGreen;">SeaGreen </option>
			<option value="SeaShell" style="background-color: SeaShell;">SeaShell </option>
			<option value="Sienna" style="background-color: Sienna;">Sienna </option>
			<option value="Silver" style="background-color: Silver;">Silver </option>
			<option value="SkyBlue" style="background-color: SkyBlue;">SkyBlue </option>
			<option value="SlateBlue" style="background-color: SlateBlue;">SlateBlue </option>
			<option value="SlateGray" style="background-color: SlateGray;">SlateGray </option>
			<option value="Snow" style="background-color: Snow;">Snow </option>
			<option value="SpringGreen" style="background-color: SpringGreen;">SpringGreen </option>
			<option value="SteelBlue" style="background-color: SteelBlue;">SteelBlue </option>
			<option value="Tan" style="background-color: Tan;">Tan </option>
			<option value="Teal" style="background-color: Teal;">Teal </option>
			<option value="Thistle" style="background-color: Thistle;">Thistle </option>
			<option value="Tomato" style="background-color: Tomato;">Tomato </option>
			<option value="Turquoise" style="background-color: Turquoise;">Turquoise </option>
			<option value="Violet" style="background-color: Violet;">Violet </option>
			<option value="Wheat" style="background-color: Wheat;">Wheat </option>
			<option value="White" style="background-color: White;">White </option>
			<option value="WhiteSmoke" style="background-color: WhiteSmoke;">WhiteSmoke </option>
			<option value="Yellow" style="background-color: Yellow;">Yellow </option>
			<option value="YellowGreen" style="background-color: YellowGreen;">YellowGreen </option>
*/
</script>
			<option value="#000000" style="background-color: rgb(0, 0, 0);">#000000 </option>
			<option value="#000033" style="background-color: rgb(0, 0, 51);">#000033 </option>
			<option value="#000066" style="background-color: rgb(0, 0, 102);">#000066 </option>
			<option value="#000099" style="background-color: rgb(0, 0, 153);">#000099 </option>
			<option value="#0000CC" style="background-color: rgb(0, 0, 204);">#0000CC </option>
			<option value="#0000FF" style="background-color: rgb(0, 0, 255);">#0000FF </option>
			<option value="#003300" style="background-color: rgb(0, 51, 0);">#003300 </option>
			<option value="#003333" style="background-color: rgb(0, 51, 51);">#003333 </option>
			<option value="#003366" style="background-color: rgb(0, 51, 102);">#003366 </option>
			<option value="#003399" style="background-color: rgb(0, 51, 153);">#003399 </option>
			<option value="#0033CC" style="background-color: rgb(0, 51, 204);">#0033CC </option>
			<option value="#0033FF" style="background-color: rgb(0, 51, 255);">#0033FF </option>
			<option value="#006600" style="background-color: rgb(0, 102, 0);">#006600 </option>
			<option value="#006633" style="background-color: rgb(0, 102, 51);">#006633 </option>
			<option value="#006666" style="background-color: rgb(0, 102, 102);">#006666 </option>
			<option value="#006699" style="background-color: rgb(0, 102, 153);">#006699 </option>
			<option value="#0066CC" style="background-color: rgb(0, 102, 204);">#0066CC </option>
			<option value="#0066FF" style="background-color: rgb(0, 102, 255);">#0066FF </option>
			<option value="#009900" style="background-color: rgb(0, 153, 0);">#009900 </option>
			<option value="#009933" style="background-color: rgb(0, 153, 51);">#009933 </option>
			<option value="#009966" style="background-color: rgb(0, 153, 102);">#009966 </option>
			<option value="#009999" style="background-color: rgb(0, 153, 153);">#009999 </option>
			<option value="#0099CC" style="background-color: rgb(0, 153, 204);">#0099CC </option>
			<option value="#0099FF" style="background-color: rgb(0, 153, 255);">#0099FF </option>
			<option value="#00CC00" style="background-color: rgb(0, 204, 0);">#00CC00 </option>
			<option value="#00CC33" style="background-color: rgb(0, 204, 51);">#00CC33 </option>
			<option value="#00CC66" style="background-color: rgb(0, 204, 102);">#00CC66 </option>
			<option value="#00CC99" style="background-color: rgb(0, 204, 153);">#00CC99 </option>
			<option value="#00CCCC" style="background-color: rgb(0, 204, 204);">#00CCCC </option>
			<option value="#00CCFF" style="background-color: rgb(0, 204, 255);">#00CCFF </option>
			<option value="#00FF00" style="background-color: rgb(0, 255, 0);">#00FF00 </option>
			<option value="#00FF33" style="background-color: rgb(0, 255, 51);">#00FF33 </option>
			<option value="#00FF66" style="background-color: rgb(0, 255, 102);">#00FF66 </option>
			<option value="#00FF99" style="background-color: rgb(0, 255, 153);">#00FF99 </option>
			<option value="#00FFCC" style="background-color: rgb(0, 255, 204);">#00FFCC </option>
			<option value="#00FFFF" style="background-color: rgb(0, 255, 255);">#00FFFF </option>
			<option value="#330000" style="background-color: rgb(51, 0, 0);">#330000 </option>
			<option value="#330033" style="background-color: rgb(51, 0, 51);">#330033 </option>
			<option value="#330066" style="background-color: rgb(51, 0, 102);">#330066 </option>
			<option value="#330099" style="background-color: rgb(51, 0, 153);">#330099 </option>
			<option value="#3300CC" style="background-color: rgb(51, 0, 204);">#3300CC </option>
			<option value="#3300FF" style="background-color: rgb(51, 0, 255);">#3300FF </option>
			<option value="#333300" style="background-color: rgb(51, 51, 0);">#333300 </option>
			<option value="#333333" style="background-color: rgb(51, 51, 51);">#333333 </option>
			<option value="#333366" style="background-color: rgb(51, 51, 102);">#333366 </option>
			<option value="#333399" style="background-color: rgb(51, 51, 153);">#333399 </option>
			<option value="#3333CC" style="background-color: rgb(51, 51, 204);">#3333CC </option>
			<option value="#3333FF" style="background-color: rgb(51, 51, 255);">#3333FF </option>
			<option value="#336600" style="background-color: rgb(51, 102, 0);">#336600 </option>
			<option value="#336633" style="background-color: rgb(51, 102, 51);">#336633 </option>
			<option value="#336666" style="background-color: rgb(51, 102, 102);">#336666 </option>
			<option value="#336699" style="background-color: rgb(51, 102, 153);">#336699 </option>
			<option value="#3366CC" style="background-color: rgb(51, 102, 204);">#3366CC </option>
			<option value="#3366FF" style="background-color: rgb(51, 102, 255);">#3366FF </option>
			<option value="#339900" style="background-color: rgb(51, 153, 0);">#339900 </option>
			<option value="#339933" style="background-color: rgb(51, 153, 51);">#339933 </option>
			<option value="#339966" style="background-color: rgb(51, 153, 102);">#339966 </option>
			<option value="#339999" style="background-color: rgb(51, 153, 153);">#339999 </option>
			<option value="#3399CC" style="background-color: rgb(51, 153, 204);">#3399CC </option>
			<option value="#3399FF" style="background-color: rgb(51, 153, 255);">#3399FF </option>
			<option value="#33CC00" style="background-color: rgb(51, 204, 0);">#33CC00 </option>
			<option value="#33CC33" style="background-color: rgb(51, 204, 51);">#33CC33 </option>
			<option value="#33CC66" style="background-color: rgb(51, 204, 102);">#33CC66 </option>
			<option value="#33CC99" style="background-color: rgb(51, 204, 153);">#33CC99 </option>
			<option value="#33CCCC" style="background-color: rgb(51, 204, 204);">#33CCCC </option>
			<option value="#33CCFF" style="background-color: rgb(51, 204, 255);">#33CCFF </option>
			<option value="#33FF00" style="background-color: rgb(51, 255, 0);">#33FF00 </option>
			<option value="#33FF33" style="background-color: rgb(51, 255, 51);">#33FF33 </option>
			<option value="#33FF66" style="background-color: rgb(51, 255, 102);">#33FF66 </option>
			<option value="#33FF99" style="background-color: rgb(51, 255, 153);">#33FF99 </option>
			<option value="#33FFCC" style="background-color: rgb(51, 255, 204);">#33FFCC </option>
			<option value="#33FFFF" style="background-color: rgb(51, 255, 255);">#33FFFF </option>
			<option value="#660000" style="background-color: rgb(102, 0, 0);">#660000 </option>
			<option value="#660033" style="background-color: rgb(102, 0, 51);">#660033 </option>
			<option value="#660066" style="background-color: rgb(102, 0, 102);">#660066 </option>
			<option value="#660099" style="background-color: rgb(102, 0, 153);">#660099 </option>
			<option value="#6600CC" style="background-color: rgb(102, 0, 204);">#6600CC </option>
			<option value="#6600FF" style="background-color: rgb(102, 0, 255);">#6600FF </option>
			<option value="#663300" style="background-color: rgb(102, 51, 0);">#663300 </option>
			<option value="#663333" style="background-color: rgb(102, 51, 51);">#663333 </option>
			<option value="#663366" style="background-color: rgb(102, 51, 102);">#663366 </option>
			<option value="#663399" style="background-color: rgb(102, 51, 153);">#663399 </option>
			<option value="#6633CC" style="background-color: rgb(102, 51, 204);">#6633CC </option>
			<option value="#6633FF" style="background-color: rgb(102, 51, 255);">#6633FF </option>
			<option value="#666600" style="background-color: rgb(102, 102, 0);">#666600 </option>
			<option value="#666633" style="background-color: rgb(102, 102, 51);">#666633 </option>
			<option value="#666666" style="background-color: rgb(102, 102, 102);">#666666 </option>
			<option value="#666699" style="background-color: rgb(102, 102, 153);">#666699 </option>
			<option value="#6666CC" style="background-color: rgb(102, 102, 204);">#6666CC </option>
			<option value="#6666FF" style="background-color: rgb(102, 102, 255);">#6666FF </option>
			<option value="#669900" style="background-color: rgb(102, 153, 0);">#669900 </option>
			<option value="#669933" style="background-color: rgb(102, 153, 51);">#669933 </option>
			<option value="#669966" style="background-color: rgb(102, 153, 102);">#669966 </option>
			<option value="#669999" style="background-color: rgb(102, 153, 153);">#669999 </option>
			<option value="#6699CC" style="background-color: rgb(102, 153, 204);">#6699CC </option>
			<option value="#6699FF" style="background-color: rgb(102, 153, 255);">#6699FF </option>
			<option value="#66CC00" style="background-color: rgb(102, 204, 0);">#66CC00 </option>
			<option value="#66CC33" style="background-color: rgb(102, 204, 51);">#66CC33 </option>
			<option value="#66CC66" style="background-color: rgb(102, 204, 102);">#66CC66 </option>
			<option value="#66CC99" style="background-color: rgb(102, 204, 153);">#66CC99 </option>
			<option value="#66CCCC" style="background-color: rgb(102, 204, 204);">#66CCCC </option>
			<option value="#66CCFF" style="background-color: rgb(102, 204, 255);">#66CCFF </option>
			<option value="#66FF00" style="background-color: rgb(102, 255, 0);">#66FF00 </option>
			<option value="#66FF33" style="background-color: rgb(102, 255, 51);">#66FF33 </option>
			<option value="#66FF66" style="background-color: rgb(102, 255, 102);">#66FF66 </option>
			<option value="#66FF99" style="background-color: rgb(102, 255, 153);">#66FF99 </option>
			<option value="#66FFCC" style="background-color: rgb(102, 255, 204);">#66FFCC </option>
			<option value="#66FFFF" style="background-color: rgb(102, 255, 255);">#66FFFF </option>
			<option value="#990000" style="background-color: rgb(153, 0, 0);">#990000 </option>
			<option value="#990033" style="background-color: rgb(153, 0, 51);">#990033 </option>
			<option value="#990066" style="background-color: rgb(153, 0, 102);">#990066 </option>
			<option value="#990099" style="background-color: rgb(153, 0, 153);">#990099 </option>
			<option value="#9900CC" style="background-color: rgb(153, 0, 204);">#9900CC </option>
			<option value="#9900FF" style="background-color: rgb(153, 0, 255);">#9900FF </option>
			<option value="#993300" style="background-color: rgb(153, 51, 0);">#993300 </option>
			<option value="#993333" style="background-color: rgb(153, 51, 51);">#993333 </option>
			<option value="#993366" style="background-color: rgb(153, 51, 102);">#993366 </option>
			<option value="#993399" style="background-color: rgb(153, 51, 153);">#993399 </option>
			<option value="#9933CC" style="background-color: rgb(153, 51, 204);">#9933CC </option>
			<option value="#9933FF" style="background-color: rgb(153, 51, 255);">#9933FF </option>
			<option value="#996600" style="background-color: rgb(153, 102, 0);">#996600 </option>
			<option value="#996633" style="background-color: rgb(153, 102, 51);">#996633 </option>
			<option value="#996666" style="background-color: rgb(153, 102, 102);">#996666 </option>
			<option value="#996699" style="background-color: rgb(153, 102, 153);">#996699 </option>
			<option value="#9966CC" style="background-color: rgb(153, 102, 204);">#9966CC </option>
			<option value="#9966FF" style="background-color: rgb(153, 102, 255);">#9966FF </option>
			<option value="#999900" style="background-color: rgb(153, 153, 0);">#999900 </option>
			<option value="#999933" style="background-color: rgb(153, 153, 51);">#999933 </option>
			<option value="#999966" style="background-color: rgb(153, 153, 102);">#999966 </option>
			<option value="#999999" style="background-color: rgb(153, 153, 153);">#999999 </option>
			<option value="#9999CC" style="background-color: rgb(153, 153, 204);">#9999CC </option>
			<option value="#9999FF" style="background-color: rgb(153, 153, 255);">#9999FF </option>
			<option value="#99CC00" style="background-color: rgb(153, 204, 0);">#99CC00 </option>
			<option value="#99CC33" style="background-color: rgb(153, 204, 51);">#99CC33 </option>
			<option value="#99CC66" style="background-color: rgb(153, 204, 102);">#99CC66 </option>
			<option value="#99CC99" style="background-color: rgb(153, 204, 153);">#99CC99 </option>
			<option value="#99CCCC" style="background-color: rgb(153, 204, 204);">#99CCCC </option>
			<option value="#99CCFF" style="background-color: rgb(153, 204, 255);">#99CCFF </option>
			<option value="#99FF00" style="background-color: rgb(153, 255, 0);">#99FF00 </option>
			<option value="#99FF33" style="background-color: rgb(153, 255, 51);">#99FF33 </option>
			<option value="#99FF66" style="background-color: rgb(153, 255, 102);">#99FF66 </option>
			<option value="#99FF99" style="background-color: rgb(153, 255, 153);">#99FF99 </option>
			<option value="#99FFCC" style="background-color: rgb(153, 255, 204);">#99FFCC </option>
			<option value="#99FFFF" style="background-color: rgb(153, 255, 255);">#99FFFF </option>
			<option value="#CC0000" style="background-color: rgb(204, 0, 0);">#CC0000 </option>
			<option value="#CC0033" style="background-color: rgb(204, 0, 51);">#CC0033 </option>
			<option value="#CC0066" style="background-color: rgb(204, 0, 102);">#CC0066 </option>
			<option value="#CC0099" style="background-color: rgb(204, 0, 153);">#CC0099 </option>
			<option value="#CC00CC" style="background-color: rgb(204, 0, 204);">#CC00CC </option>
			<option value="#CC00FF" style="background-color: rgb(204, 0, 255);">#CC00FF </option>
			<option value="#CC3300" style="background-color: rgb(204, 51, 0);">#CC3300 </option>
			<option value="#CC3333" style="background-color: rgb(204, 51, 51);">#CC3333 </option>
			<option value="#CC3366" style="background-color: rgb(204, 51, 102);">#CC3366 </option>
			<option value="#CC3399" style="background-color: rgb(204, 51, 153);">#CC3399 </option>
			<option value="#CC33CC" style="background-color: rgb(204, 51, 204);">#CC33CC </option>
			<option value="#CC33FF" style="background-color: rgb(204, 51, 255);">#CC33FF </option>
			<option value="#CC6600" style="background-color: rgb(204, 102, 0);">#CC6600 </option>
			<option value="#CC6633" style="background-color: rgb(204, 102, 51);">#CC6633 </option>
			<option value="#CC6666" style="background-color: rgb(204, 102, 102);">#CC6666 </option>
			<option value="#CC6699" style="background-color: rgb(204, 102, 153);">#CC6699 </option>
			<option value="#CC66CC" style="background-color: rgb(204, 102, 204);">#CC66CC </option>
			<option value="#CC66FF" style="background-color: rgb(204, 102, 255);">#CC66FF </option>
			<option value="#CC9900" style="background-color: rgb(204, 153, 0);">#CC9900 </option>
			<option value="#CC9933" style="background-color: rgb(204, 153, 51);">#CC9933 </option>
			<option value="#CC9966" style="background-color: rgb(204, 153, 102);">#CC9966 </option>
			<option value="#CC9999" style="background-color: rgb(204, 153, 153);">#CC9999 </option>
			<option value="#CC99CC" style="background-color: rgb(204, 153, 204);">#CC99CC </option>
			<option value="#CC99FF" style="background-color: rgb(204, 153, 255);">#CC99FF </option>
			<option value="#CCCC00" style="background-color: rgb(204, 204, 0);">#CCCC00 </option>
			<option value="#CCCC33" style="background-color: rgb(204, 204, 51);">#CCCC33 </option>
			<option value="#CCCC66" style="background-color: rgb(204, 204, 102);">#CCCC66 </option>
			<option value="#CCCC99" style="background-color: rgb(204, 204, 153);">#CCCC99 </option>
			<option value="#CCCCCC" style="background-color: rgb(204, 204, 204);">#CCCCCC </option>
			<option value="#CCCCFF" style="background-color: rgb(204, 204, 255);">#CCCCFF </option>
			<option value="#CCFF00" style="background-color: rgb(204, 255, 0);">#CCFF00 </option>
			<option value="#CCFF33" style="background-color: rgb(204, 255, 51);">#CCFF33 </option>
			<option value="#CCFF66" style="background-color: rgb(204, 255, 102);">#CCFF66 </option>
			<option value="#CCFF99" style="background-color: rgb(204, 255, 153);">#CCFF99 </option>
			<option value="#CCFFCC" style="background-color: rgb(204, 255, 204);">#CCFFCC </option>
			<option value="#CCFFFF" style="background-color: rgb(204, 255, 255);">#CCFFFF </option>
			<option value="#FF0000" style="background-color: rgb(255, 0, 0);">#FF0000 </option>
			<option value="#FF0033" style="background-color: rgb(255, 0, 51);">#FF0033 </option>
			<option value="#FF0066" style="background-color: rgb(255, 0, 102);">#FF0066 </option>
			<option value="#FF0099" style="background-color: rgb(255, 0, 153);">#FF0099 </option>
			<option value="#FF00CC" style="background-color: rgb(255, 0, 204);">#FF00CC </option>
			<option value="#FF00FF" style="background-color: rgb(255, 0, 255);">#FF00FF </option>
			<option value="#FF3300" style="background-color: rgb(255, 51, 0);">#FF3300 </option>
			<option value="#FF3333" style="background-color: rgb(255, 51, 51);">#FF3333 </option>
			<option value="#FF3366" style="background-color: rgb(255, 51, 102);">#FF3366 </option>
			<option value="#FF3399" style="background-color: rgb(255, 51, 153);">#FF3399 </option>
			<option value="#FF33CC" style="background-color: rgb(255, 51, 204);">#FF33CC </option>
			<option value="#FF33FF" style="background-color: rgb(255, 51, 255);">#FF33FF </option>
			<option value="#FF6600" style="background-color: rgb(255, 102, 0);">#FF6600 </option>
			<option value="#FF6633" style="background-color: rgb(255, 102, 51);">#FF6633 </option>
			<option value="#FF6666" style="background-color: rgb(255, 102, 102);">#FF6666 </option>
			<option value="#FF6699" style="background-color: rgb(255, 102, 153);">#FF6699 </option>
			<option value="#FF66CC" style="background-color: rgb(255, 102, 204);">#FF66CC </option>
			<option value="#FF66FF" style="background-color: rgb(255, 102, 255);">#FF66FF </option>
			<option value="#FF9900" style="background-color: rgb(255, 153, 0);">#FF9900 </option>
			<option value="#FF9933" style="background-color: rgb(255, 153, 51);">#FF9933 </option>
			<option value="#FF9966" style="background-color: rgb(255, 153, 102);">#FF9966 </option>
			<option value="#FF9999" style="background-color: rgb(255, 153, 153);">#FF9999 </option>
			<option value="#FF99CC" style="background-color: rgb(255, 153, 204);">#FF99CC </option>
			<option value="#FF99FF" style="background-color: rgb(255, 153, 255);">#FF99FF </option>
			<option value="#FFCC00" style="background-color: rgb(255, 204, 0);">#FFCC00 </option>
			<option value="#FFCC33" style="background-color: rgb(255, 204, 51);">#FFCC33 </option>
			<option value="#FFCC66" style="background-color: rgb(255, 204, 102);">#FFCC66 </option>
			<option value="#FFCC99" style="background-color: rgb(255, 204, 153);">#FFCC99 </option>
			<option value="#FFCCCC" style="background-color: rgb(255, 204, 204);">#FFCCCC </option>
			<option value="#FFCCFF" style="background-color: rgb(255, 204, 255);">#FFCCFF </option>
			<option value="#FFFF00" style="background-color: rgb(255, 255, 0);">#FFFF00 </option>
			<option value="#FFFF33" style="background-color: rgb(255, 255, 51);">#FFFF33 </option>
			<option value="#FFFF66" style="background-color: rgb(255, 255, 102);">#FFFF66 </option>
			<option value="#FFFF99" style="background-color: rgb(255, 255, 153);">#FFFF99 </option>
			<option value="#FFFFCC" style="background-color: rgb(255, 255, 204);">#FFFFCC </option>
			<option value="#FFFFFF" style="background-color: rgb(255, 255, 255);">#FFFFFF </option>
			</select>
			<input name="btnG" class="submit" value="Change Color!" type="submit">
			<a href="javascript: resetColors()" name="link">reset colors!</a>
		</div></form>
	</div>
</div>
{include file="page_footer.tpl"}
