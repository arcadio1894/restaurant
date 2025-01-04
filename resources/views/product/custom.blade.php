@extends('layouts.app')

@section('menu-active', 'active')

@section('text-header')
    <h2 class="pt-5">
        Personaliza tu Pizza
    </h2>

@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/customPizza.css') }}">
@endsection

@section('content')
    <section class="py-2">
        <div class="container">
            <div class="toppings-list">
                <!-- Size Item -->
                <div class="topping-item">
                    <div style="display: flex; align-items: center;">
                        <img src="{{ asset('/images/icons/pizza.png') }}" alt="Size">
                        <div class="topping-name">Tamaño</div>
                    </div>
                    <div class="action-button" data-size onclick="toggleSizeDropdown(this)">
                        <div class="circle mr-2"></div>
                        <i class="far fa-plus-square"></i>

                    </div>
                </div>
                <div id="size-options" class="dropdown-options">
                    <div class="option-controls">
                        <div>
                            <input type="radio" id="personal" name="size" value="personal">
                            <label for="personal" style="border: 1px solid #ddd;">Personal</label>
                        </div>
                        <div>
                            <input type="radio" id="large" name="size" value="large">
                            <label for="large" style="border: 1px solid #ddd;">Grande</label>
                        </div>
                        <div>
                            <input type="radio" id="familiar" name="size" value="familiar" checked>
                            <label for="familiar" style="border: 1px solid #ddd;">Familiar</label>
                        </div>
                    </div>

                </div>

                <!-- Salsa Item -->
                <div class="topping-item">
                    <div style="display: flex; align-items: center;">
                        <img src="{{ asset('/images/icons/salsa-de-tomate.png') }}" alt="Salsa">
                        <div class="topping-name">Salsa</div>
                    </div>
                    <div class="action-button" data-salsa onclick="toggleToppingDropdown('salsa-options', this, 'wholeSalsa', 'img-whole-Salsa', '{{ asset('/images/icons/on_all.webp') }}')">
                        <div class="circle mr-2"></div>
                        <i class="far fa-plus-square"></i>

                    </div>
                </div>
                <div id="salsa-options" class="dropdown-options">
                    <div class="option-controls">
                        <div class="radio-group">
                            <div>
                                <input type="radio" id="leftSalsa" name="Salsa" value="leftSalsa" data-image-inactive="{{ asset('/images/icons/off_left.webp') }}" data-image-active="{{ asset('/images/icons/on_left.webp') }}" onclick="changeImage('Salsa', 'left')">
                                <label for="leftSalsa" class="radio-label">
                                    <img id="img-left-Salsa" class="imagen-radio" src="{{ asset('/images/icons/off_left.webp') }}" alt="Left Salsa">
                                    Izq.
                                </label>
                            </div>
                            <div>
                                <input type="radio" checked id="wholeSalsa" name="Salsa" value="wholeSalsa" data-image-inactive="{{ asset('/images/icons/off_all.webp') }}" data-image-active="{{ asset('/images/icons/on_all.webp') }}" onclick="changeImage('Salsa', 'whole')">
                                <label for="wholeSalsa" class="radio-label">
                                    <img id="img-whole-Salsa" class="imagen-radio" src="{{ asset('/images/icons/on_all.webp') }}" alt="Whole Salsa">
                                    Todo
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="rightSalsa" name="Salsa" value="rightSalsa" data-image-inactive="{{ asset('/images/icons/off_right.webp') }}" data-image-active="{{ asset('/images/icons/on_right.webp') }}" onclick="changeImage('Salsa', 'right')">
                                <label for="rightSalsa" class="radio-label">
                                    <img id="img-right-Salsa" class="imagen-radio" src="{{ asset('/images/icons/off_right.webp') }}" alt="Right Salsa">
                                    Der.
                                </label>
                            </div>
                        </div>
                        <div class="extra-group ml-1">
                            <label class="switch mt-3">
                                <input type="checkbox" id="extraSalsa">
                                <span class="slider"></span>
                            </label>
                            <p>Extra</p>
                        </div>
                    </div>

                </div>

                <!-- Cheese Item -->
                <div class="topping-item">
                    <div style="display: flex; align-items: center;">
                        <img src="{{ asset('/images/icons/queso.png') }}" alt="Queso">
                        <div class="topping-name">Queso</div>
                    </div>
                    <div class="action-button" data-cheese onclick="toggleToppingDropdown('cheese-options', this, 'wholeCheese', 'img-whole-Cheese', '{{ asset('/images/icons/on_all.webp') }}')">
                        <div class="circle mr-2"></div>
                        <i class="far fa-plus-square"></i>

                    </div>
                </div>
                <div id="cheese-options" class="dropdown-options">
                    <div class="option-controls">
                        <div class="radio-group">
                            <div>
                                <input type="radio" id="leftCheese" name="Cheese" value="leftCheese" data-image-inactive="{{ asset('/images/icons/off_left.webp') }}" data-image-active="{{ asset('/images/icons/on_left.webp') }}" onclick="changeImage('Cheese', 'left')">
                                <label for="leftCheese" class="radio-label">
                                    <img id="img-left-Cheese" class="imagen-radio" src="{{ asset('/images/icons/off_left.webp') }}" alt="Left Cheese">
                                    Izq.
                                </label>
                            </div>
                            <div>
                                <input type="radio" checked id="wholeCheese" name="Cheese" value="wholeCheese" data-image-inactive="{{ asset('/images/icons/off_all.webp') }}" data-image-active="{{ asset('/images/icons/on_all.webp') }}" onclick="changeImage('Cheese', 'whole')">
                                <label for="wholeCheese" class="radio-label">
                                    <img id="img-whole-Cheese" class="imagen-radio" src="{{ asset('/images/icons/on_all.webp') }}" alt="Whole Cheese">
                                    Todo
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="rightCheese" name="Cheese" value="rightCheese" data-image-inactive="{{ asset('/images/icons/off_right.webp') }}" data-image-active="{{ asset('/images/icons/on_right.webp') }}" onclick="changeImage('Cheese', 'right')">
                                <label for="rightCheese" class="radio-label">
                                    <img id="img-right-Cheese" class="imagen-radio" src="{{ asset('/images/icons/off_right.webp') }}" alt="Right Cheese">
                                    Der.
                                </label>
                            </div>
                        </div>
                        <div class="extra-group ml-1">
                            <label class="switch mt-3">
                                <input type="checkbox" id="extraCheese">
                                <span class="slider"></span>
                            </label>
                            <p>Extra</p>
                        </div>
                    </div>

                </div>

                <!-- Meats Header -->
                <div class="meats-header">
                    <div style="display: flex; align-items: center;">
                        <div class="topping-name">CARNES</div>
                    </div>
                </div>

                <!-- Topping Item -->
                @foreach( $toppingMeats as $toppingMeat )
                    <div class="topping-item">
                        <div style="display: flex; align-items: center;">
                            <img src="{{ asset('/images/icons/'.$toppingMeat->image) }}" alt="{{ $toppingMeat->name }}">
                            <div class="topping-name">{{ $toppingMeat->name }}</div>
                        </div>
                        <div class="action-button" data-meat="{{ $toppingMeat->slug }}" onclick="toggleToppingDropdown('{{ $toppingMeat->slug }}-options', this, 'whole{{ $toppingMeat->slug }}', 'img-whole-{{ $toppingMeat->slug }}', '{{ asset('/images/icons/on_all.webp') }}')">
                            <div class="circle mr-2"></div>
                            <i class="far fa-plus-square"></i>
                        </div>
                    </div>
                    <div id="{{ $toppingMeat->slug }}-options" class="dropdown-options">
                        <div class="option-controls">
                            <div class="radio-group">
                                <div>
                                    <input type="radio" id="left{{ $toppingMeat->slug }}" name="{{ $toppingMeat->slug }}" value="left{{ $toppingMeat->slug }}" data-image-inactive="{{ asset('/images/icons/off_left.webp') }}" data-image-active="{{ asset('/images/icons/on_left.webp') }}" onclick="changeImage('{{ $toppingMeat->slug }}', 'left')">
                                    <label for="left{{ $toppingMeat->slug }}" class="radio-label">
                                        <img id="img-left-{{ $toppingMeat->slug }}" class="imagen-radio" src="{{ asset('/images/icons/off_left.webp') }}" alt="Left {{ $toppingMeat->slug }}">
                                        Izq.
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" checked id="whole{{ $toppingMeat->slug }}" name="{{ $toppingMeat->slug }}" value="whole{{ $toppingMeat->slug }}" data-image-inactive="{{ asset('/images/icons/off_all.webp') }}" data-image-active="{{ asset('/images/icons/on_all.webp') }}" onclick="changeImage('{{ $toppingMeat->slug }}', 'whole')">
                                    <label for="whole{{ $toppingMeat->slug }}" class="radio-label">
                                        <img id="img-whole-{{ $toppingMeat->slug }}" class="imagen-radio" src="{{ asset('/images/icons/on_all.webp') }}" alt="Whole {{ $toppingMeat->slug }}">
                                        Todo
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="right{{ $toppingMeat->slug }}" name="{{ $toppingMeat->slug }}" value="right{{ $toppingMeat->slug }}" data-image-inactive="{{ asset('/images/icons/off_right.webp') }}" data-image-active="{{ asset('/images/icons/on_right.webp') }}" onclick="changeImage('{{ $toppingMeat->slug }}', 'right')">
                                    <label for="right{{ $toppingMeat->slug }}" class="radio-label">
                                        <img id="img-right-{{ $toppingMeat->slug }}" class="imagen-radio" src="{{ asset('/images/icons/off_right.webp') }}" alt="Right {{ $toppingMeat->slug }}">
                                        Der.
                                    </label>
                                </div>
                            </div>
                            <div class="extra-group ml-1">
                                <label class="switch mt-3">
                                    <input type="checkbox" id="extra{{ $toppingMeat->slug }}">
                                    <span class="slider"></span>
                                </label>
                                <p>Extra</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- Veggies Header -->
                <div class="meats-header">
                    <div style="display: flex; align-items: center;">
                        <div class="topping-name">VEGETALES</div>
                    </div>
                </div>
                @foreach( $toppingVeggies as $toppingVeggie )
                    <div class="topping-item">
                        <div style="display: flex; align-items: center;">
                            <img src="{{ asset('/images/icons/'.$toppingVeggie->image) }}" alt="{{ $toppingVeggie->name }}">
                            <div class="topping-name">{{ $toppingVeggie->name }}</div>
                        </div>
                        <div class="action-button" data-veggie="{{ $toppingVeggie->slug }}" onclick="toggleToppingDropdown('{{ $toppingVeggie->slug }}-options', this, 'whole{{ $toppingVeggie->slug }}', 'img-whole-{{ $toppingVeggie->slug }}', '{{ asset('/images/icons/on_all.webp') }}')">
                            <div class="circle mr-2"></div>
                            <i class="far fa-plus-square"></i>
                        </div>
                    </div>
                    <div id="{{ $toppingVeggie->slug }}-options" class="dropdown-options">
                        <div class="option-controls">
                            <!-- Contenedor con borde para las opciones de radio -->
                            <div class="radio-group">
                                <div>
                                    <input type="radio" id="left{{ $toppingVeggie->slug }}" name="{{ $toppingVeggie->slug }}" value="left{{ $toppingVeggie->slug }}" data-image-inactive="{{ asset('/images/icons/off_left.webp') }}" data-image-active="{{ asset('/images/icons/on_left.webp') }}" onclick="changeImage('{{ $toppingVeggie->slug }}', 'left')">
                                    <label for="left{{ $toppingVeggie->slug }}" class="radio-label">
                                        <img id="img-left-{{ $toppingVeggie->slug }}" class="imagen-radio" src="{{ asset('/images/icons/off_left.webp') }}" alt="Left {{ $toppingVeggie->slug }}">
                                        Izq.
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" checked id="whole{{ $toppingVeggie->slug }}" name="{{ $toppingVeggie->slug }}" value="whole{{ $toppingVeggie->slug }}" data-image-inactive="{{ asset('/images/icons/off_all.webp') }}" data-image-active="{{ asset('/images/icons/on_all.webp') }}" onclick="changeImage('{{ $toppingVeggie->slug }}', 'whole')">
                                    <label for="whole{{ $toppingVeggie->slug }}" class="radio-label">
                                        <img id="img-whole-{{ $toppingVeggie->slug }}" class="imagen-radio" src="{{ asset('/images/icons/on_all.webp') }}" alt="Whole {{ $toppingVeggie->slug }}">
                                        Todo
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="right{{ $toppingVeggie->slug }}" name="{{ $toppingVeggie->slug }}" value="right{{ $toppingVeggie->slug }}" data-image-inactive="{{ asset('/images/icons/off_right.webp') }}" data-image-active="{{ asset('/images/icons/on_right.webp') }}" onclick="changeImage('{{ $toppingVeggie->slug }}', 'right')">
                                    <label for="right{{ $toppingVeggie->slug }}" class="radio-label">
                                        <img id="img-right-{{ $toppingVeggie->slug }}" class="imagen-radio" src="{{ asset('/images/icons/off_right.webp') }}" alt="Right {{ $toppingVeggie->slug }}">
                                        Der.
                                    </label>
                                </div>
                            </div>

                            <!-- Contenedor con borde para el switch de "Extra" -->
                            <div class="extra-group ml-1">
                                <label class="switch mt-3">
                                    <input type="checkbox" id="extra{{ $toppingVeggie->slug }}">
                                    <span class="slider"></span>
                                </label>
                                <p>Extra</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="toppings-list mt-4">
                <button class="btn btn-primary btn-block py-3" id="btn-confirm">Confirmar</button>
            </div>


        </div>
    </section>
    <!-- content -->

@endsection

@section('scripts')
    <script>
        function toggleDropdown(id, button) {
            const options = document.getElementById(id);
            const isActive = options.classList.contains('active');

            // Alternar el despliegue del contenido
            options.classList.toggle('active');

            // Cambiar el ícono y estado del botón
            const icon = button.querySelector('i');
            const circle = button.querySelector('.circle');

            if (isActive) {
                icon.classList.remove('fa-check-square');
                icon.classList.add('fa-plus-square');
                button.classList.remove('selected');
            } else {
                icon.classList.remove('fa-plus-square');
                icon.classList.add('fa-check-square');
                button.classList.add('selected');
            }
        }

        function toggleSizeDropdown(button) {
            const options = document.getElementById('size-options');
            const isActive = options.classList.contains('active');

            // Alternar el despliegue del contenido
            options.classList.toggle('active');

            // Cambiar el ícono y estado del botón
            const icon = button.querySelector('i');
            const circle = button.querySelector('.circle');

            if (isActive) {
                icon.classList.remove('fa-check-square');
                icon.classList.add('fa-plus-square');
                button.classList.remove('selected');

                // 💡 Resetear los inputs radio a su estado por defecto
                resetSizeOptions();
            } else {
                icon.classList.remove('fa-plus-square');
                icon.classList.add('fa-check-square');
                button.classList.add('selected');
            }
        }

        function resetSizeOptions() {
            // Seleccionar el input radio por defecto (Familiar)
            const defaultOption = document.getElementById('familiar');
            if (defaultOption) {
                defaultOption.checked = true;
            }
        }

        function changeImage(slug, position) {
            console.log(slug);
            // Obtener todos los radio buttons del grupo correspondiente
            const radioButtons = document.getElementsByName(slug);
            console.log(radioButtons);
            // Iterar sobre cada radio button en el grupo
            radioButtons.forEach(radio => {
                console.log(radio.id);
                const imageId = `img-${radio.id.replace(slug, '').toLowerCase()}-${slug}`;
                const imageElement = document.getElementById(imageId);
                console.log(imageId);
                console.log(imageElement);
                if (imageElement) {
                    if (radio.checked) {
                        // Cambiar a la imagen activa si está seleccionado
                        imageElement.src = radio.getAttribute('data-image-active');
                    } else {
                        // Cambiar a la imagen inactiva si no está seleccionado
                        imageElement.src = radio.getAttribute('data-image-inactive');
                    }
                }
            });
        }

        function toggleToppingDropdown(id, button, defaultInputId, defaultImageId, defaultImageSrc) {
            const options = document.getElementById(id);
            const isActive = options.classList.contains('active');
            // Alternar el despliegue del contenido
            options.classList.toggle('active');

            // Cambiar el ícono y estado del botón
            const icon = button.querySelector('i');
            const circle = button.querySelector('.circle');

            if (isActive) {
                icon.classList.remove('fa-check-square');
                icon.classList.add('fa-plus-square');
                button.classList.remove('selected');

                // 💡 Resetear los inputs y las imágenes
                resetToppingOptions(defaultInputId, defaultImageId, defaultImageSrc, id);
            } else {
                icon.classList.remove('fa-plus-square');
                icon.classList.add('fa-check-square');
                button.classList.add('selected');
            }
        }

        function resetToppingOptions(defaultInputId, defaultImageId, defaultImageSrc, optionsId) {
            // Seleccionar el input radio por defecto y la imagen correspondiente
            const defaultInput = document.getElementById(defaultInputId);
            const defaultImage = document.getElementById(defaultImageId);

            console.log(defaultInput);
            console.log(defaultImage);

            if (defaultInput) {
                defaultInput.checked = true;
            }
            if (defaultImage) {
                defaultImage.src = defaultImageSrc;
            }

            // 💡 Resetear las imágenes de los inputs no seleccionados a sus valores inactivos
            const options = document.getElementById(optionsId);

            console.log(options);

            const radios = options.querySelectorAll('input[type="radio"]');

            console.log(options);

            radios.forEach(radio => {
                console.log(radio);
                const imageId = `img-${radio.id.replace(/([a-z])([A-Z])/g, '$1-$2')}`;
                console.log(imageId);

                const imageElement = document.getElementById(imageId);
                console.log(imageElement);

                if (!radio.checked && imageElement) {
                    imageElement.src = radio.getAttribute('data-image-inactive');
                }
            });

            // 💡 Desactivar el switch "Extra" si está activado
            const switchInput = options.querySelector('input[type="checkbox"]');
            if (switchInput && switchInput.checked) {
                switchInput.checked = false;
            }
        }

        $("#btn-confirm").on('click', confirmCustomPizza);
        
        function confirmCustomPizza() {
            // Verificar si el botón de tamaño está activado
            const sizeButton = $(".action-button[data-size]");
            if (!sizeButton.hasClass("selected")) {
                alert("Por favor, activa el botón de tamaño antes de continuar.");
                return;
            }

            // Verificar si el botón de salsa está activado
            const salsaButton = $(".action-button[data-salsa]");
            if (!salsaButton.hasClass("selected")) {
                alert("Por favor, activa el botón de salsa antes de continuar.");
                return;
            }

            // Verificar si el botón de queso está activado
            const cheeseButton = $(".action-button[data-cheese]");
            if (!cheeseButton.hasClass("selected")) {
                alert("Por favor, activa el botón de queso antes de continuar.");
                return;
            }

            // Validar el tamaño seleccionado
            const selectedSize = $("input[name='size']:checked").val();
            if (!selectedSize) {
                alert("Por favor, selecciona un tamaño antes de confirmar.");
                return;
            }

            // Validar la salsa seleccionada
            const selectedSalsa = $("input[name='Salsa']:checked").val();
            if (!selectedSalsa) {
                alert("Por favor, selecciona una salsa antes de confirmar.");
                return;
            }

            // Verificar el estado del switch de salsa
            const extraSalsaChecked = $("#extraSalsa").is(":checked");

            // Validar el queso seleccionado
            const selectedCheese = $("input[name='Cheese']:checked").val();
            if (!selectedCheese) {
                alert("Por favor, selecciona un queso antes de confirmar.");
                return;
            }

            // Verificar el estado del switch de queso
            const extraCheeseChecked = $("#extraCheese").is(":checked");

            // Procesar las carnes activas
            const meatSelections = [];
            $("[data-meat].selected").each(function () {
                const meatSlug = $(this).attr("data-meat");

                // Verificar si tiene un radio seleccionado
                const selectedMeat = $(`input[name='${meatSlug}']:checked`).val();
                if (selectedMeat) {
                    // Verificar el estado del switch extra
                    const extraMeatChecked = $(`#extra${meatSlug}`).is(":checked");

                    // Agregar a la lista de selecciones solo las carnes activas
                    meatSelections.push({
                        meat: meatSlug,
                        selection: selectedMeat,
                        extra: extraMeatChecked
                    });
                }
            });

            // Procesar las carnes activas
            const veggieSelections = [];
            $("[data-veggie].selected").each(function () {
                const veggieSlug = $(this).attr("data-veggie");

                // Verificar si tiene un radio seleccionado
                const selectedVeggie = $(`input[name='${veggieSlug}']:checked`).val();
                if (selectedVeggie) {
                    // Verificar el estado del switch extra
                    const extraVeggieChecked = $(`#extra${veggieSlug}`).is(":checked");

                    // Agregar a la lista de selecciones solo las carnes activas
                    veggieSelections.push({
                        veggie: veggieSlug,
                        selection: selectedVeggie,
                        extra: extraVeggieChecked
                    });
                }
            });

            // Imprime los resultados en la consola (o realiza cualquier acción necesaria)
            console.log("Tamaño seleccionado:", selectedSize);
            console.log("Salsa seleccionada:", selectedSalsa);
            console.log("¿Extra salsa activado?:", extraSalsaChecked ? "Sí" : "No");
            console.log("Queso seleccionado:", selectedCheese);
            console.log("¿Extra queso activado?:", extraCheeseChecked ? "Sí" : "No");
            console.log("Carnes seleccionadas:", meatSelections);
            console.log("Vegetales seleccionadas:", veggieSelections);

            // Mostrar una alerta de resumen
            let meatSummary = meatSelections.length > 0
                ? meatSelections.map(meat =>
                    `- ${meat.meat}: ${meat.selection} (${meat.extra ? "Extra" : "Normal"})`
                ).join("\n")
                : "No se seleccionaron carnes.";

            // Mostrar una alerta de resumen
            let veggieSummary = veggieSelections.length > 0
                ? veggieSelections.map(veggie =>
                    `- ${veggie.veggie}: ${veggie.selection} (${veggie.extra ? "Extra" : "Normal"})`
                ).join("\n")
                : "No se seleccionaron carnes.";

            // Mostrar una alerta de resumen
            alert(`Resumen de tu pizza:\n
                Tamaño: ${selectedSize}\n
                Salsa: ${selectedSalsa}\n
                ¿Extra salsa?: ${extraSalsaChecked ? "Sí" : "No"}\n
                Queso: ${selectedCheese}\n
                ¿Extra queso?: ${extraCheeseChecked ? "Sí" : "No"}\n
                Carnes:\n${meatSummary}\n
                Vegetales:\n${veggieSummary}`)
        }
    </script>
@endsection
