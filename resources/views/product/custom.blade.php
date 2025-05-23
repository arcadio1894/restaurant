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
                        {{--<div>
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
                        </div>--}}
                        @foreach ($sizes as $size)
                            <div>
                                <input type="radio" id="{{ $size['id'] }}" name="size" value="{{ $size['id'] }}" {{ $size['checked'] }}>
                                <label for="{{ $size['id'] }}" style="border: 1px solid #ddd;">{{ $size['label'] }}</label>
                            </div>
                        @endforeach
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
                        <div class="action-button" data-meat="{{ $toppingMeat->slug }}" data-realName="{{ $toppingMeat->name }}" onclick="toggleToppingDropdown('{{ $toppingMeat->slug }}-options', this, 'whole{{ $toppingMeat->slug }}', 'img-whole-{{ $toppingMeat->slug }}', '{{ asset('/images/icons/on_all.webp') }}')">
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
                                    <input type="checkbox" id="extra{{ $toppingMeat->slug }}" onclick="toggleExtra('{{ $toppingMeat->slug }}')">
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
                        <div class="action-button" data-veggie="{{ $toppingVeggie->slug }}" data-realName="{{ $toppingVeggie->name }}" onclick="toggleToppingDropdown('{{ $toppingVeggie->slug }}-options', this, 'whole{{ $toppingVeggie->slug }}', 'img-whole-{{ $toppingVeggie->slug }}', '{{ asset('/images/icons/on_all.webp') }}')">
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
                                    <input type="checkbox" id="extra{{ $toppingVeggie->slug }}" onclick="toggleExtra('{{ $toppingVeggie->slug }}')">
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
        window.baseImageUrl = "{{ asset('images/icons/') }}";
    </script>
    <script>
        let toppingsLeft = 0;
        let toppingsRight = 0;
        let maxToppingsLeft = 4;
        let maxToppingsRight = 4;

        let arrayToppings = [];

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
            //console.log(slug);
            // Buscar el topping en el arrayToppings
            const toppingIndex = arrayToppings.findIndex(topping => topping.slug === slug);

            if (toppingIndex !== -1) {
                const currentTopping = arrayToppings[toppingIndex];
                const previousPosition = currentTopping.position; // Posición anterior
                const extra = currentTopping.extra; // Estado del extra
                let newPosition = null;

                // Obtener todos los radio buttons del grupo correspondiente
                const radioButtons = document.getElementsByName(slug);

                // Detectar cuál posición está actualmente seleccionada
                radioButtons.forEach(radio => {
                    if (radio.checked) {
                        const radioId = radio.id; // Obtener el id del radio seleccionado
                        if (radioId.startsWith('left')) {
                            newPosition = 'left';
                        } else if (radioId.startsWith('right')) {
                            newPosition = 'right';
                        } else if (radioId.startsWith('whole')) {
                            newPosition = 'whole';
                        }
                    }
                });

                // Asegurarse de que se haya detectado una nueva posición
                //console.log(newPosition);
                if (newPosition) {
                    // Restar de las variables globales según la posición anterior
                    if (previousPosition === 'left') {
                        toppingsLeft -= extra ? 1.5 : 1; // Restar 1.5 si extra está activado, de lo contrario 1
                    } else if (previousPosition === 'right') {
                        toppingsRight -= extra ? 1.5 : 1;
                    } else if (previousPosition === 'whole') {
                        toppingsLeft -= extra ? 1.5 : 1;
                        toppingsRight -= extra ? 1.5 : 1;
                    }

                    // Sumar a las variables globales según la nueva posición
                    if (newPosition === 'left') {
                        toppingsLeft += extra ? 1.5 : 1; // Sumar 1.5 si extra está activado, de lo contrario 1
                    } else if (newPosition === 'right') {
                        toppingsRight += extra ? 1.5 : 1;
                    } else if (newPosition === 'whole') {
                        toppingsLeft += extra ? 1.5 : 1;
                        toppingsRight += extra ? 1.5 : 1;
                    }

                    // Actualizar la posición en el elemento del array
                    currentTopping.position = newPosition;

                }

                console.log(arrayToppings);
                console.log("ToppingsLeft: "+toppingsLeft);
                console.log("ToppingsRight: "+toppingsRight);
                verificarToppings();
            }

            // Obtener todos los radio buttons del grupo correspondiente
            const radioButtons = document.getElementsByName(slug);
            //console.log(radioButtons);
            // Iterar sobre cada radio button en el grupo
            radioButtons.forEach(radio => {
                //console.log(radio.id);
                const imageId = `img-${radio.id.replace(slug, '').toLowerCase()}-${slug}`;
                const imageElement = document.getElementById(imageId);
                //console.log(imageId);
                //console.log(imageElement);
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
            // Verificar si el size está seleccionado
            const selectedSize = $('[data-size].selected');
            if (selectedSize.length === 0) {
                $.confirm({
                    title: 'Tamaño no seleccionado',
                    content: 'Por favor selecciona el tamaño de la pizza antes de añadir ingredientes.',
                    type: 'red',
                    buttons: {
                        ok: {
                            text: 'Entendido',
                            btnClass: 'btn-red',
                        }
                    }
                });
                return; // Salir de la función si no se ha seleccionado un tamaño
            }

            const options = document.getElementById(id);
            const isActive = options.classList.contains('active');
            const slug = id.replace('-options', ''); // Obtener el slug del topping a partir del ID del dropdown

            // Alternar el despliegue del contenido
            options.classList.toggle('active');

            // Cambiar el ícono y estado del botón
            const icon = button.querySelector('i');
            const circle = button.querySelector('.circle');

            // Verificar si es un ingrediente excluido (salsa o queso)
            const isExcluded = button.hasAttribute('data-salsa') || button.hasAttribute('data-cheese');

            if (isActive) {
                // Cerrar el dropdown
                icon.classList.remove('fa-check-square');
                icon.classList.add('fa-plus-square');
                button.classList.remove('selected');

                if (!isExcluded) {
                    // Buscar el topping en el arrayToppings
                    const toppingIndex = arrayToppings.findIndex(topping => topping.slug === slug);

                    if (toppingIndex !== -1) {
                        // Obtener los datos actuales del topping
                        const currentTopping = arrayToppings[toppingIndex];
                        const position = currentTopping.position; // Posición actual ('left', 'right', 'whole')
                        const extra = currentTopping.extra; // Estado actual de 'extra' (0 o 1)

                        // Ajustar las variables según la posición y el estado de extra
                        if (position === 'left') {
                            toppingsLeft -= (extra === 1) ? 1.5 : 1;
                        } else if (position === 'right') {
                            toppingsRight -= (extra === 1) ? 1.5 : 1;
                        } else if (position === 'whole') {
                            toppingsLeft -= (extra === 1) ? 1.5 : 1;
                            toppingsRight -= (extra === 1) ? 1.5 : 1;
                        }

                        // Asegurarse de no tener valores negativos
                        toppingsLeft = Math.max(toppingsLeft, 0);
                        toppingsRight = Math.max(toppingsRight, 0);

                        // Eliminar el topping del array
                        arrayToppings.splice(toppingIndex, 1);

                        // Mostrar el estado actualizado del array en la consola
                        //console.log(`Topping eliminado: ${slug}`);
                        console.log(arrayToppings);
                        console.log("ToppingsLeft: "+toppingsLeft);
                        console.log("ToppingsRight: "+toppingsRight);
                        verificarToppings();
                    }
                }

                // Resetear los inputs y las imágenes
                resetToppingOptions(defaultInputId, defaultImageId, defaultImageSrc, id);
            } else {
                // Abrir el dropdown
                icon.classList.remove('fa-plus-square');
                icon.classList.add('fa-check-square');
                button.classList.add('selected');

                if (!isExcluded) {
                    // Verificar si el topping ya existe en el array
                    const existingTopping = arrayToppings.find(topping => topping.slug === slug);
                    if (!existingTopping) {
                        // Si no existe, agregarlo con valores por defecto
                        arrayToppings.push({
                            slug: slug,
                            position: 'whole', // Por defecto la posición es 'whole'
                            extra: 0 // Por defecto 'extra' no está seleccionado
                        });
                    }

                    // Verificar si el valor por defecto está seleccionado
                    const defaultInput = document.getElementById(defaultInputId);
                    if (defaultInput && defaultInput.checked) {
                        // Sumar +1 a ambos lados si el valor por defecto es "whole"
                        toppingsLeft++;
                        toppingsRight++;
                    }

                    // Mostrar el estado actualizado del array en la consola
                    console.log(arrayToppings);
                    console.log("ToppingsLeft: " + toppingsLeft);
                    console.log("ToppingsRight: " + toppingsRight);
                    verificarToppings();
                }
            }
        }

        function toggleExtra(slug) {
            // Buscar el topping en el arrayToppings
            const toppingIndex = arrayToppings.findIndex(topping => topping.slug === slug);

            if (toppingIndex !== -1) {
                const currentTopping = arrayToppings[toppingIndex];
                const currentPosition = currentTopping.position; // Obtener la posición actual
                const checkbox = document.getElementById(`extra${slug}`); // Obtener el checkbox

                if (checkbox.checked) {
                    // Checkbox activado: sumar valores
                    if (currentPosition === 'left') {
                        toppingsLeft += 0.5;
                    } else if (currentPosition === 'right') {
                        toppingsRight += 0.5;
                    } else if (currentPosition === 'whole') {
                        toppingsLeft += 0.5;
                        toppingsRight += 0.5;
                    }

                    // Actualizar el estado "extra" en el array
                    currentTopping.extra = 1; // Marcar como extra activado
                } else {
                    // Checkbox desactivado: restar valores
                    if (currentPosition === 'left') {
                        toppingsLeft -= 0.5;
                    } else if (currentPosition === 'right') {
                        toppingsRight -= 0.5;
                    } else if (currentPosition === 'whole') {
                        toppingsLeft -= 0.5;
                        toppingsRight -= 0.5;
                    }

                    // Actualizar el estado "extra" en el array
                    currentTopping.extra = 0; // Marcar como extra desactivado
                }

                // Mostrar el estado actualizado en consola
                //console.log(`Topping actualizado: ${slug}`);
                //console.log(`Estado extra: ${checkbox.checked ? 'Activado' : 'Desactivado'}`);
                console.log(arrayToppings);
                console.log("ToppingsLeft: "+toppingsLeft);
                console.log("ToppingsRight: "+toppingsRight);
                verificarToppings();
            }
        }

        function verificarToppings() {
            // Detectar el tamaño seleccionado
            const selectedSize = document.querySelector('input[name="size"]:checked').value;
            let mensaje = "";
            let title = "";
            // Determinar el límite según el tamaño
            let toppingLimit = 0;
            if (selectedSize === 'personal') {
                toppingLimit = 3;
                title = "🍕 <strong style='font-size: 16px; line-height: 1.4;'>¡Atención Chef!</strong>";
                mensaje = "<div style='font-size: 16px; line-height: 1.4;'>" +
                    "Has seleccionado más de 3 toppings en un lado de la pizza.<br>" +
                    "Para que tu pizza personal quede perfectamente horneada, te recomendamos elegir hasta 3 toppings por lado.<br>" +
                    "Si agregas más, la cocción no será la mejor y queremos que disfrutes cada bocado. 😋<br>" +
                    "<br>" +
                    "👉 Por favor, ajusta los toppings por lado y sigue creando tu pizza perfecta. 🍕<br>" +
                    "</div>";
            } else if (selectedSize === 'large' || selectedSize === 'familiar') {
                toppingLimit = 4;
                title = "🍕 <strong style='font-size: 16px; line-height: 1.4;'>¡Cuidado con los toppings, Chef!</strong>";
                mensaje = "<div style='font-size: 16px; line-height: 1.4;'>" +
                    "Has seleccionado más de 4 toppings en un lado de la pizza.<br>" +
                    "Para que tu pizza familiar quede deliciosa y bien cocinada, asegúrate de no agregar más de 4 toppings por lado.<br>" +
                    "Si eliges más, la masa podría no cocinarse bien y eso afectaría el resultado. 🍽<br>" +
                    "<br>" +
                    "👉 Por favor, ajusta los toppings por lado para garantizar una pizza de calidad <strong>FUEGO Y MASA</strong>. 🔥🍕<br>" +
                    "</div>";
            }

            // Validar los valores de toppingsLeft y toppingsRight
            if (toppingsLeft > toppingLimit || toppingsRight > toppingLimit) {
                // Mostrar el mensaje de alerta con jQuery Confirm
                $.confirm({
                    title: title,
                    content: mensaje,
                    type: 'red',
                    buttons: {
                        aceptar: {
                            text: 'Entendido',
                            btnClass: 'btn-red',
                        },
                    },
                });
            }
        }

        function resetToppingOptions(defaultInputId, defaultImageId, defaultImageSrc, optionsId) {
            // Seleccionar el input radio por defecto y la imagen correspondiente
            const defaultInput = document.getElementById(defaultInputId);
            const defaultImage = document.getElementById(defaultImageId);

            /*console.log(defaultInput);
            console.log(defaultImage);*/

            if (defaultInput) {
                defaultInput.checked = true;
            }
            if (defaultImage) {
                defaultImage.src = defaultImageSrc;
            }

            // 💡 Resetear las imágenes de los inputs no seleccionados a sus valores inactivos
            const options = document.getElementById(optionsId);

            /*console.log(options);*/

            const radios = options.querySelectorAll('input[type="radio"]');

            /*console.log(options);*/

            radios.forEach(radio => {
                /*console.log(radio);*/
                const imageId = `img-${radio.id.replace(/([a-z])([A-Z])/g, '$1-$2')}`;
                /*console.log(imageId);*/

                const imageElement = document.getElementById(imageId);
                /*console.log(imageElement);*/

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
                $.alert({
                    title: 'Atención',
                    content: 'Por favor, activa el botón de tamaño antes de continuar.',
                    type: 'orange', // Tipo de alerta (opciones: blue, green, orange, red)
                    boxWidth: '350px',
                    theme: 'modern',
                    useBootstrap: false,
                    buttons: {
                        ok: {
                            text: 'Entendido',
                            btnClass: 'btn-orange',
                        }
                    }
                });
                return;
            }

            // Validar el tamaño seleccionado
            const selectedSize = $("input[name='size']:checked").val();
            if (!selectedSize) {
                $.alert({
                    title: 'Atención',
                    content: 'Por favor, selecciona un tamaño antes de confirmar.',
                    type: 'orange',
                    boxWidth: '350px',
                    theme: 'modern',
                    useBootstrap: false,
                    buttons: {
                        ok: {
                            text: 'Entendido',
                            btnClass: 'btn-orange',
                        }
                    }
                });
                return;
            }

            // Validar la salsa seleccionada
            const salsaButton = $(".action-button[data-salsa]");
            let selectedSalsa = "";
            let extraSalsaChecked = false;

            if (!salsaButton.hasClass("selected")) {
                selectedSalsa = "No";
            } else {
                selectedSalsa = $("input[name='Salsa']:checked").val();
                extraSalsaChecked = $("#extraSalsa").is(":checked");
            }

            // Validar el queso seleccionado
            const cheeseButton = $(".action-button[data-cheese]");
            let selectedCheese = "";
            let extraCheeseChecked = false;

            if (!cheeseButton.hasClass("selected")) {
                selectedCheese = "No";
            } else {
                selectedCheese = $("input[name='Cheese']:checked").val();
                extraCheeseChecked = $("#extraCheese").is(":checked");
            }

            // Procesar las carnes activas
            const meatSelections = [];
            $(`[data-meat].selected`).each(function () {
                const meatSlug = $(this).attr("data-meat");
                const meatRealName = $(this).attr("data-realName");
                const selectedMeat = $(`input[name='${meatSlug}']:checked`).val();
                const extraMeatChecked = $(`#extra${meatSlug}`).is(":checked");

                if (selectedMeat) {
                    meatSelections.push({
                        meat: meatSlug,
                        realName: meatRealName,
                        selection: selectedMeat,
                        extra: extraMeatChecked
                    });
                }
            });

            // Procesar los vegetales activos
            const veggieSelections = [];
            $(`[data-veggie].selected`).each(function () {
                const veggieSlug = $(this).attr("data-veggie");
                const veggieRealName = $(this).attr("data-realName");
                const selectedVeggie = $(`input[name='${veggieSlug}']:checked`).val();
                const extraVeggieChecked = $(`#extra${veggieSlug}`).is(":checked");

                if (selectedVeggie) {
                    veggieSelections.push({
                        veggie: veggieSlug,
                        realName: veggieRealName,
                        selection: selectedVeggie,
                        extra: extraVeggieChecked
                    });
                }
            });

            // Crear el array con el formato solicitado
            const pizzaData = {
                size: selectedSize,
                salsa: {
                    seleccion: salsaButton.hasClass("selected") ? "Sí" : "No",
                    elecciones: salsaButton.hasClass("selected") ? {
                        tipo: selectedSalsa,
                        extra: extraSalsaChecked ? "Sí" : "No"
                    } : null
                },
                queso: {
                    seleccion: cheeseButton.hasClass("selected") ? "Sí" : "No",
                    elecciones: cheeseButton.hasClass("selected") ? {
                        tipo: selectedCheese,
                        extra: extraCheeseChecked ? "Sí" : "No"
                    } : null
                },
                meats: meatSelections.map(meat => ({
                    [meat.meat]: {
                        seleccion: "Sí",
                        elecciones: {
                            tipo: meat.selection,
                            extra: meat.extra ? "Sí" : "No"
                        }
                    }
                })),
                veggies: veggieSelections.map(veggie => ({
                    [veggie.veggie]: {
                        seleccion: "Sí",
                        elecciones: {
                            tipo: veggie.selection,
                            extra: veggie.extra ? "Sí" : "No"
                        }
                    }
                }))
            };

            console.log(pizzaData);

            // Generar el contenido del modal con formato mejorado
            const formatPosition = (type) => {
                if (!type) return "";
                const position = type.match(/left|right|whole/)?.[0]; // Extrae la posición
                if (position === "left") return "A la izquierda";
                if (position === "right") return "A la derecha";
                if (position === "whole") return "En todo";
                return "";
            };

            const meatContent = meatSelections.map(meat => {
                const icon = `<img src="${window.baseImageUrl}/${meat.meat}.png" alt="${meat.meat}" style="width: 24px; height: 24px;">`;
                const position = formatPosition(meat.selection);
                const extra = meat.extra ? "Con extra" : "Sin extra";
                return `<p>${icon} ${meat.realName}: Sí - ${position} - ${extra}</p>`;
            }).join("");

            const veggieContent = veggieSelections.map(veggie => {
                const icon = `<img src="${window.baseImageUrl}/${veggie.veggie}.png" alt="${veggie.veggie}" style="width: 24px; height: 24px;">`;
                const position = formatPosition(veggie.selection);
                const extra = veggie.extra ? "Con extra" : "Sin extra";
                return `<p>${icon} ${veggie.realName}: Sí - ${position} - ${extra}</p>`;
            }).join("");

            // Mostrar el resumen en un modal
            $.confirm({
                title: 'Confirmación de Pizza',
                content: `
                    <p><img src="${window.baseImageUrl}/pizza.png" alt="Size" style="width: 24px; height: 24px;"> Tamaño: ${selectedSize}</p>
                    <p><img src="${window.baseImageUrl}/salsa-de-tomate.png" alt="Salsa" style="width: 24px; height: 24px;"> Salsa: ${pizzaData.salsa.seleccion} - ${formatPosition(pizzaData.salsa.elecciones?.tipo || "Nada")} - ${pizzaData.salsa.elecciones?.extra === "Sí" ? "Con extra" : "Sin extra"}</p>
                    <p><img src="${window.baseImageUrl}/queso.png" alt="Queso" style="width: 24px; height: 24px;"> Queso: ${pizzaData.queso.seleccion} - ${formatPosition(pizzaData.queso.elecciones?.tipo || "Nada")} - ${pizzaData.queso.elecciones?.extra === "Sí" ? "Con extra" : "Sin extra"}</p>
                    <p><strong>Carnes:</strong></p>
                    ${meatContent || "<p>Ninguna seleccionada</p>"}
                    <p><strong>Vegetales:</strong></p>
                    ${veggieContent || "<p>Ninguna seleccionada</p>"}
                `,
                theme: 'modern', // Puedes probar otros temas como 'bootstrap', 'modern', 'dark'
                boxWidth: '350px', // Ajusta el ancho de la ventana
                useBootstrap: false, // Usa estilos independientes de Bootstrap
                buttons: {
                    confirmar: {
                        text: 'Confirmar',
                        btnClass: 'btn-green',
                        action: function () {
                            // Hacer una llamada AJAX para enviar los datos al backend
                            $.ajax({
                                url: '/save/custom/product',
                                method: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify(pizzaData),
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Incluye el token CSRF en los encabezados
                                },
                                success: function (response) {
                                    console.log(response);
                                    // Logica que inserta en el carrito
                                    // Procesar los toppings del backend
                                    let toppings = [];
                                    $.each(response.toppings, function (key, value) {
                                        if (Array.isArray(value)) {
                                            toppings = toppings.concat(value);
                                        } else {
                                            toppings.push(value);
                                        }
                                    });

                                    // Obtener el carrito del localStorage
                                    let cart = JSON.parse(localStorage.getItem('cart')) || [];

                                    // Crear el nuevo producto a agregar al carrito
                                    let newCartItem = {
                                        product_id: response.product_id,
                                        product_type_id: response.product_type_id,
                                        product_type_name: response.product_type_name,
                                        options: null, // Según tu especificación
                                        quantity: response.quantity,
                                        user_id: response.user_id,
                                        custom: response.custom,
                                        reward: false,
                                        total: response.total,
                                        toppings: toppings,
                                        cart_index: generateUUID()
                                    };

                                    // Agregar el nuevo producto al carrito
                                    cart.push(newCartItem);

                                    // Guardar el carrito actualizado en el localStorage
                                    localStorage.setItem('cart', JSON.stringify(cart));

                                    // Actualizar la cantidad del carrito
                                    updateCartQuantity();

                                    $.alert({
                                        title: 'Éxito',
                                        content: 'Producto agregado al carrito',
                                        onClose: function () {
                                            window.location.href = response.url_redirect; // Redirigir a la ruta del carrito
                                        }
                                    });
                                },
                                error: function (xhr, status, error) {
                                    $.alert('Hubo un problema al confirmar el pedido.');
                                }
                            });
                        }
                    },
                    volver: {
                        text: 'Volver',
                        btnClass: 'btn-red',
                        action: function () {
                            // Cerrar el modal
                        }
                    }
                }
            });
        }

        function updateCartQuantity() {
            // Si no está autenticado, obtener la cantidad desde localStorage
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Contar el número de productos únicos
            let totalItems = cart.length;

            // Actualizar el contenido del span
            $("#quantityCart").html(`(${totalItems})`);
            $("#quantityCart2").html(`(${totalItems})`);
        }

        function generateUUID() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                const r = Math.random() * 16 | 0;
                const v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

    </script>
@endsection
