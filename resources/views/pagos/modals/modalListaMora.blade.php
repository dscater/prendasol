<div id="modalListaMora" class="modal fade" data-backdrop="static">
	<div class="modal-dialog modal-dialog-scrollable" style="width: 900px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Lista Moras</h5>
			</div>			
			<div class="modal-body">
				<div class="row">          
					<div class="col-md-12">
						<label>Seleccione:</label>
						<select name="txtDiasRango" id="txtDiasRango" class="form-control">
							<option value="30" selected>30 días</option>
							<option value="60">60 días</option>
							<option value="90">90 días</option>
							<option value="120">120 días</option>
							<option value="150">150 días</option>
							<option value="180">180 días</option>
							<option value="210">210 días</option>
							<option value="240">240 días</option>
							<option value="270">270 días</option>
							<option value="300">300 días</option>
							<option value="330">330 días</option>
							<option value="360">360 días</option>
							<option value="390">390 días</option>
							<option value="MAS">MUCHO MÁS</option>
						</select>	
					</div>  
			        <div class="col-md-12">            
		                <section id="contListaMoras" style="height:auto;overflow: auto;"></section>        
			        </div>
			    </div>					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
				<a href="{{route("pagos.listadoMorasExcel")}}?dias=30" data-url="{{route("pagos.listadoMorasExcel")}}" target="_blank" id="btnExportExcelListaMoras" class="btn btn-success">Exportar</a>
			</div>			
		</div>
	</div>
</div>




