<div id="modalCreatePersona" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Registrar Persona</h5>
			</div>

			<form id="frmPersona">
				<div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Nombres: ')!!}
                                {!! Form::text('txtNombres', null, array('placeholder' => 'Ingrese Nombres ','class' => 'form-control','id'=>'txtNombres')) !!}                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Primer Apellido: ')!!}
                                {!! Form::text('txtPaterno', null, array('placeholder' => 'Ingrese Primer Apellido','class' => 'form-control','id'=>'txtPaterno')) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Segundo Apellido: ')!!}
                                {!! Form::text('txtMaterno', null, array('placeholder' => ' Ingrese Segundo Apellido','class' => 'form-control','id'=>'txtMaterno')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!!Form::label('Persona','Nro. CI:')!!}
                                {!! Form::text('txtCI', null, array('placeholder' => 'Ingrese Identificación ','class' => 'form-control','id'=>'txtCI')) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!!Form::label('Persona','Complemento')!!}
                                <input type="text" name="txtComplemento" id="txtComplemento" maxlength="2" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control" placeholder="Complemento">
                                {{-- {!! Form::text('txtComplemento', null, array('placeholder' => 'Complemento','class' => 'form-control','id'=>'txtComplemento')) !!} --}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!!Form::label('Persona','Expedido:')!!}
                                <select class="form-control" id="ddlExpedido" name="ddlExpedido" Value="">
                                    <option></option>
                                    <option value="1">
                                        Chuquisaca
                                    </option>
                                    <option value="2">
                                        La Paz
                                    </option>
                                    <option value="0">
                                        Oruro
                                    </option>                                    
                                    <option value="3">
                                        Cochabamba
                                    </option>
                                    <option value="4">
                                        Santa Cruz
                                    </option>
                                    
                                    <option value="5">
                                        Potosi
                                    </option>
                                    <option value="6">
                                        Tarija
                                    </option>
                                    <option value="7">
                                        Beni
                                    </option>
                                    <option value="8">
                                        Pando
                                    </option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-lg-3">Sexo:</label>
                                <div class="col-lg-9">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" id="rdoSexo" name="rdoSexo" value="5">
                                            Masculino
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" id="rdoSexo" name="rdoSexo" value="6">
                                            Femenino
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Fecha de Nacimiento:')!!}
                                {{-- <div class="input-group input-append date" id="datePickerFN">
                                    <input type="text" class="form-control" id="txtFechaNacimiento" name="txtFechaNacimiento" placeholder="Ingrese Fecha de Nacimiento" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div> --}}
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id="txtFechaNacimiento" name="txtFechaNacimiento" class="form-control" data-mask>
                                </div> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Estado Civil:')!!}
                                <span class="block input-icon input-icon-right">                                
                                    <select id="ddlEstadocivil" name="ddlEstadocivil" data-placeholder="Elige Estado Civil" class="select">
                                        <option></option>
                                        @if(!empty($estadoCivil))
                                            @foreach($estadoCivil as $ecivil)
                                                <option value="{{ $ecivil->id }}"> {{ $ecivil->catalogodescripcion }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Correo:')!!}                            
                                {!! Form::text('txtCorreo', null, array('placeholder' => 'Ingrese correo ejemplo@gmail.com','class' => 'form-control','id'=>'txtCorreo')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Teléfono Domicilio:')!!}
                                {!! Form::text('txtTelefonoDomicilio', null, array('placeholder' => 'Ingrese Telefono Domicilio', 'class' => 'form-control','id'=>'txtTelefonoDomicilio')) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Teléfono Trabajo:')!!}
                                {!! Form::text('txtTelefonoTrabajo', null, array('placeholder' => 'Ingrese Telefono Trabajo', 'class' => 'form-control','id'=>'txtTelefonoTrabajo')) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Dirección Trabajo:')!!}
                                {!! Form::text('txtDireccionTrabajo', null, array('placeholder' => 'Ingrese dirección Trabajo','class' => 'form-control','id'=>'txtDireccionTrabajo')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Celular:')!!}
                                {!! Form::text('txtCelular', null, array('placeholder' => 'Ingrese Celular', 'class' => 'form-control','id'=>'txtCelular')) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Celular 2:')!!}
                                {!! Form::text('txtCelular2', null, array('placeholder' => 'Ingrese Celular', 'class' => 'form-control','id'=>'txtCelular2')) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!!Form::label('Persona','Domicilio:')!!}
                                {!! Form::text('txtDomicilio', null, array('placeholder' => 'Ingrese Domicilio', 'class' => 'form-control','id'=>'txtDomicilio')) !!}
                            </div>
                        </div>
                        
                    </div>
                </div>                
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal" onclick="fnLimpiarControles();">Cancelar</button>
					{!!link_to('#',$title='Registrar', $attributes=['id'=>'btnRegistrar','class'=>'btn btn-primary'], $secure=null)!!}
				</div>
			</form>
		</div>
	</div>
</div>