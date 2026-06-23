<main id="main_container">
    <section id="contcts_container">
        <h3>Entre em contato</h3>
        <p>
            Preencha o formulário ao lado e entraremos em contato com você o mais
            rápido possivel.
        </p>

        <div id="cards_container">
            <!-- ################ INICIO CARD PHONE ##############  -->
            <a href="tel:+5511963067419" target="_blank" class="contact-card phone">
                <div class="card-icon">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div class="card-infos">
                    <p>Telefone</p>
                    <span> (11)96306-7419 </span>
                </div>
            </a>
            <!-- ################ FIM CARD PHONE ##############  -->

            <!-- ################ INICIO CARD LOCALIZAÇÃO##############  -->
            <a href="#" target="_blank" class="contact-card location">
                <div class="card-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div class="card-infos">
                    <p>Localização</p>
                    <span>
                        Rua Jesus Te Ama, 777, Bairro: Felicidade, Cidade: Ceú - Estado:
                        Feliz
                    </span>
                </div>
            </a>
            <!-- ################ FIM CARD LOCALIZAÇÃO##############  -->

            <!-- ################ INICIO CARD EMAIL##############  -->
            <a href="mailto:email@gmail.com" target="_blank" class="contact-card email">
                <div class="card-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="card-infos">
                    <p>E-mail</p>
<<<<<<< HEAD
<<<<<<< HEAD
                    <span> email@gmail.com </span>
=======
                    <span> contato.traducaidiomas@adminfo.dev.br</span>
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
=======
                    <span> contato.traducaidiomas@adminfo.dev.br</span>
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
                </div>
            </a>
            <!-- ################ FIM CARD EMAIL##############  -->

            <!-- ################ INICIO  CARD WHATSAP##############  -->
            <a href="https://api.whatsapp.com/send?phone=" target="_blank" class="contact-card whatsapp">
                <div class="card-icon">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <div class="card-infos">
                    <p>whatsapp</p>
<<<<<<< HEAD
<<<<<<< HEAD
                    <span> (11)99999-9999-999 </span>
=======
                    <span> (11)99612140</span>
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
=======
                    <span> (11)99612140</span>
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
                </div>
            </a>
            <!-- ################ FIM CARD WHATSAP##############  -->
        </div>

        <h4>Nos siga nas redes sociais</h4>

        <div class="social_media_icons">
            <a href="#" class="icon-link twitter">
                <i class="fa-brands fa-x-twitter"></i>
            </a>
            <a href="#" class="icon-link facebook">
                <i class="fa-brands fa-facebook"></i>
            </a>
            <a href="#" class="icon-link linkedin">
                <i class="fa-brands fa-linkedin"></i>
            </a>
            <a href="#" class="icon-link instagram">
                <i class="fa-brands fa-instagram"></i>
            </a>
        </div>
    </section>

    <section id="contacts_form_container">
        <h3>Contatar</h3>

        @if (session('sucesso'))
            <div id="alerta-sucesso">
                {{ session('sucesso') }}
            </div>
        @endif

        @if ($errors->any())
            <ul class="alert-erros">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('contato.enviar') }}" method="POST" id="contact_form">
            @csrf

            <div class="input-group">
                <label for="name"> Nome </label>
                <input type="text" id="name" name="nome" value="{{ old('nome') }}" required />
            </div>

            <div class="input-group">
                <label for="email"> E-mail </label>
                <input type="text" id="email" name="email" value="{{ old('email') }}" required />
            </div>

            <div class="input-group">
                <label for="subjet"> Assunto </label>
                <input type="text" id="subjet" name="assunto" value="{{ old('assunto') }}" required />
            </div>

            <div class="input-group">
                <label for="message"> Mensagem </label>
                <textarea name="mensagem" id="message" required rows="8">{{ old('mensagem') }}</textarea>
            </div>

            <button type="submit">Enviar mensagem</button>

        </form>
    </section>


    <script>
    const alerta = document.getElementById('alerta-sucesso');
    if (alerta) {
        setTimeout(() => {
            alerta.style.opacity = '0';
            setTimeout(() => alerta.remove(), 500);
        }, 3000);
    }
</script>

</main>
