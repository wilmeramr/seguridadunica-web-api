<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="{{ asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<script>
    $(document).ready(function() {
        App.init();
    });
</script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('plugins/sweetalerts/sweetalert2.min.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('plugins/select2/custom-select2.js') }}"></script>
<script src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('plugins/flatpickr/es.js') }}"></script>




<script src="{{ asset('plugins/notification/snackbar/snackbar.min.js') }}"></script>
<script src="{{ asset('plugins/nicescroll/nicescroll.js') }}"></script>
<script src="{{ asset('plugins/currency/currency.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script src="{{ asset('plugins/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/dash_2.js') }}"></script>
<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="{{ asset('js/apexcharts.js') }}"></script>
<script src="{{ asset('js/app.js') }}" ></script>

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
@livewireScripts()


<script>
    var cha = 'Emergencias.{{auth()->user()->lote()->first()->country()->first()->co_id}}';

    window.Echo.private(cha).listen('UserEmergenciaEmit',(e)=>
    {

        $('#theModalemergencias').modal('show');

        const eme = document.getElementById('eme');
        eme.innerText = 'Lote: '+ e.emergencia.eme_lote_name+'  Usuario: '+ e.emergencia.eme_user_name;

      //  alert(e.emergencia.eme_id);
    });
{{--      var pusher = new Pusher('57006292f34221faf48a', {
        cluster:'us2',
        encrypted: true,
         userAuthentication: { endpoint: "/pusher_user_auth.php"}
      });
//alert({{auth()->user()->lote()->first()->country()->first()->co_id}});
      // Subscribe to the channel we specified in our Laravel Event

     // alert(cha);
      var channel = pusher.subscribe('private-Emergencias.{{auth()->user()->lote()->first()->country()->first()->co_id}}');

      channel.bind('UserEmergenciaEmit', function(data) {
        alert(data);
      });
  --}}
    function noty(msg, option = 1){
        $snackbar.show({
            text: msg.toUpperCase(),
            actionText:'CERRAR',
            actionTextColor: '#fff',
            backgroundColor: option==1 ? '#3b3f5c': '#e7515a',
            pos: 'top-right'
        });
    }

    const activePage = window.location.pathname;
    const Links = document.querySelectorAll('li a').forEach(link=>{
        if(link.href.includes(`${activePage}`)){
            link.parentNode.classList.add('active');
           console.log(link);
        }
    })


 {{--     $(".tagging").select2({
        tags: true,
        dropdownParent: $("#theModal")
    });  --}}

    var f1 = flatpickr('.basicFlatpickr', {

        dateFormat: "d-m-Y",
       locale: "es"

    });

    var f3 = flatpickr(document.getElementById('rangeCalendarFlatpickr'), {
        mode: "range",
        dateFormat: "d-m-Y",
        locale: "es"

    });

    var f4 = flatpickr('.flatpickrTime', {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        defaultDate: "09:00",
        locale: "es",

    });

    var f2 = flatpickr('.flatpickrTimeEvento', {
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        locale: "es"
    });
</script>
