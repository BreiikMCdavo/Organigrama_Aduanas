<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Servidor Público</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .tabs {
            display: flex;
            border-bottom: 2px solid #e0e0e0;
            background: #f8f9fa;
        }
        .tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            font-weight: bold;
            color: #666;
            transition: all 0.3s;
            border: none;
            background: none;
            font-size: 16px;
        }
        .tab.active {
            color: #007bff;
            border-bottom: 3px solid #007bff;
            background: white;
        }
        .tab-content {
            display: none;
            padding: 30px;
            animation: fadeIn 0.5s;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        .row > div {
            flex: 1;
        }
        h3 {
            color: #007bff;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .image-preview {
            margin-top: 10px;
            max-width: 200px;
        }
        .image-preview img {
            width: 100%;
            border-radius: 5px;
        }
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }
        .radio-group label {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: normal;
        }
        .radio-group input {
            width: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="tabs">
            <button class="tab active" onclick="showTab('item')">ITEM</button>
            <button class="tab" onclick="showTab('consultoria')">CONSULTORÍA</button>
        </div>

        <form action="{{ url('/servidores') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipo" id="tipo" value="item">

            <!-- FORMULARIO ITEM -->
            <div id="tab-item" class="tab-content active">
                <h3>Datos del Ítem</h3>
                
                <div class="row">
                    <div class="form-group">
                        <label>Nº Ítem:</label>
                        <input type="text" name="numero_item">
                    </div>
                    <div class="form-group">
                        <label>Memorandum:</label>
                        <input type="text" name="cite_memorandum">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Cargo:</label>
                        <input type="text" name="cargo" placeholder="Ej: Analista de Sistemas">
                    </div>
                    <div class="form-group">
                        <label>Designación:</label>
                        <select name="designacion">
                            <option value="">Seleccionar...</option>
                            <option value="Interinato">Interinato</option>
                            <option value="Comisión">Comisión</option>
                            <option value="Propiedad">Propiedad</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Nombres:</label>
                        <input type="text" name="nombre" placeholder="Nombres completos">
                    </div>
                    <div class="form-group">
                        <label>Apellido Paterno:</label>
                        <input type="text" name="apellido_paterno">
                    </div>
                    <div class="form-group">
                        <label>Apellido Materno:</label>
                        <input type="text" name="apellido_materno">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Fecha de ingreso a la Aduana:</label>
                        <input type="date" name="fecha_ingreso_aduana">
                    </div>
                    <div class="form-group">
                        <label>Fecha de inicio cargo:</label>
                        <input type="date" name="fecha_inicio_cargo">
                    </div>
                </div>

                <h3>Inamovilidad</h3>
                
                <div class="form-group">
                    <label>1. Asignación Familiar:</label>
                    <textarea name="asignacion_familiar_desc" rows="2" placeholder="Ingresar descripción..."></textarea>
                    <div style="margin-top: 10px;">
                        <label>Grado:</label>
                        <select name="asignacion_familiar_grado">
                            <option value="">Seleccionar</option>
                            <option value="G">G</option>
                            <option value="MG">MG</option>
                            <option value="M">M</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>2. Casos especiales:</label>
                    <textarea name="casos_especiales_desc" rows="2" placeholder="Ingresar descripción..."></textarea>
                    <div style="margin-top: 10px;">
                        <label>Grado:</label>
                        <select name="casos_especiales_grado">
                            <option value="">Seleccionar</option>
                            <option value="G">G</option>
                            <option value="MG">MG</option>
                            <option value="M">M</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>3. Discapacidad Ley N° 223:</label>
                    <textarea name="discapacidad_desc" rows="2" placeholder="Ingresar descripción..."></textarea>
                    <div style="margin-top: 10px;">
                        <label>Grado:</label>
                        <select name="discapacidad_grado">
                            <option value="">Seleccionar</option>
                            <option value="G">G</option>
                            <option value="MG">MG</option>
                            <option value="M">M</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- FORMULARIO CONSULTORÍA -->
            <div id="tab-consultoria" class="tab-content">
                <h3>Datos de Consultoría</h3>

                <div class="row">
                    <div class="form-group">
                        <label>Contrato N°:</label>
                        <input type="text" name="contrato_numero" placeholder="Ej: CON-2024-095">
                    </div>
                    <div class="form-group">
                        <label>Cargo:</label>
                        <input type="text" name="cargo_consultoria" placeholder="Ej: Consultor Jurídico">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Nombres:</label>
                        <input type="text" name="nombre" placeholder="Nombres completos">
                    </div>
                    <div class="form-group">
                        <label>Apellido Paterno:</label>
                        <input type="text" name="apellido_paterno">
                    </div>
                    <div class="form-group">
                        <label>Apellido Materno:</label>
                        <input type="text" name="apellido_materno">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Fecha de ingreso a la Aduana:</label>
                        <input type="date" name="fecha_ingreso_aduana">
                    </div>
                    <div class="form-group">
                        <label>Fecha de inicio contrato:</label>
                        <input type="date" name="fecha_inicio_contrato">
                    </div>
                    <div class="form-group">
                        <label>Fecha de fin contrato:</label>
                        <input type="date" name="fecha_fin_contrato">
                    </div>
                </div>

                <h3>Inamovilidad</h3>

                <div class="form-group">
                    <label>1. Asignación Familiar:</label>
                    <textarea name="asignacion_familiar_desc" rows="2" placeholder="Ingresar descripción..."></textarea>
                    <div style="margin-top: 10px;">
                        <label>Grado:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="asignacion_familiar_grado" value="G"> G</label>
                            <label><input type="radio" name="asignacion_familiar_grado" value="MG"> MG</label>
                            <label><input type="radio" name="asignacion_familiar_grado" value="M"> M</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>2. Casos especiales:</label>
                    <textarea name="casos_especiales_desc" rows="2" placeholder="Ingresar descripción..."></textarea>
                    <div style="margin-top: 10px;">
                        <label>Grado:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="casos_especiales_grado" value="G"> G</label>
                            <label><input type="radio" name="casos_especiales_grado" value="MG"> MG</label>
                            <label><input type="radio" name="casos_especiales_grado" value="M"> M</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>3. Discapacidad Ley N° 223:</label>
                    <textarea name="discapacidad_desc" rows="2" placeholder="Ingresar descripción..."></textarea>
                    <div style="margin-top: 10px;">
                        <label>Grado:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="discapacidad_grado" value="G"> G</label>
                            <label><input type="radio" name="discapacidad_grado" value="MG"> MG</label>
                            <label><input type="radio" name="discapacidad_grado" value="M"> M</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fotografía (común para ambos) -->
            <div style="padding: 0 30px 30px 30px;">
                <div class="form-group">
                    <label>📷 Fotografía:</label>
                    <input type="file" name="fotografia" accept="image/*" onchange="previewImage(event)">
                    <div class="image-preview" id="imagePreview"></div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">💾 Guardar</button>
                    <a href="{{ url('/servidores') }}">
                        <button type="button" class="btn btn-secondary">❌ Cancelar</button>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        function showTab(tab) {
            // Ocultar todos los contenidos
            document.getElementById('tab-item').classList.remove('active');
            document.getElementById('tab-consultoria').classList.remove('active');
            
            // Desactivar todos los tabs
            document.querySelectorAll('.tab').forEach(btn => btn.classList.remove('active'));
            
            // Mostrar el tab seleccionado
            if (tab === 'item') {
                document.getElementById('tab-item').classList.add('active');
                document.querySelector('.tab:first-child').classList.add('active');
                document.getElementById('tipo').value = 'item';
            } else {
                document.getElementById('tab-consultoria').classList.add('active');
                document.querySelector('.tab:last-child').classList.add('active');
                document.getElementById('tipo').value = 'consultoria';
            }
        }
        
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>
</html>