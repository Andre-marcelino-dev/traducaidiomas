<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Traduca | Portal do Aluno</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
<meta name="color-scheme" content="light dark" />
<meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
<meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

<link rel="preload" href="{{ asset('dash/css/adminlte.css')}}" as="style" />

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
  crossorigin="anonymous"
  media="print"
  onload="this.media = 'all'"
/>

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
  crossorigin="anonymous"
/>

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
  crossorigin="anonymous"
/>

<link rel="stylesheet" href="{{ asset('dash/css/adminlte.css')}}" />

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
  crossorigin="anonymous"
/>

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
  crossorigin="anonymous"
/>

<script>
    const titles = [
        '✨ Portal do Aluno | Traducaidiomas',
        '✨ Inglês | Avançado',
        '✨ Espanhol | Intermediário',
        '✨ Italiano | Iniciante'
    ];
    let i = 0;
    setInterval(() => {
        document.title = titles[i % titles.length];
        i++;
    }, 2000);
</script>