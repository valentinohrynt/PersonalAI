$(document).ready(function() {
    var savedLang = localStorage.getItem('lang');
    if (savedLang) {
        $('select[name="lang"] option[value="' + savedLang + '"]').prop('selected', true);
    }
    var converter = new showdown.Converter();
    new Typed('#label', {
        strings: ['Feel free to ask me anything!'],
        typeSpeed: 20,
        loop: false,
        showCursor: false
    });
    var counter = 0;
    var send = true;
    var typed;
    var lang = $('select[name="lang"]').val();
    var userInput = $('#user_prompt').val();

    $('select[name="lang"]').on('change', function() {
        var selectedLang = $(this).val();
        localStorage.setItem('lang', selectedLang);
    });

    $('textarea').on('input', function () {
        this.style.height = 'auto';
    
        this.style.height =
            (this.scrollHeight) + 'px';
    });

    function nl2brAndEscapeHtml(str) {
        return str.replace(/&/g, '&amp;')
                  .replace(/</g, '&lt;')
                  .replace(/>/g, '&gt;')
                  .replace(/\n/g, '<br>');
    }

    function getResponse() {
        $.ajax({
            type: 'POST',
            url: 'send-prompt',
            data: {
                lang : lang,
                user_prompt: userInput
            },
            success: function(response) {
                $('#user_prompt').val('');
                $('textarea').css('height', 'auto');
                var response = JSON.parse(response);
                var formattedResponse = converter.makeHtml(response.response);
                return typed = new Typed('#response' + counter, {
                    strings: [formattedResponse],
                    typeSpeed: 1,
                    loop: false,
                    showCursor: false,
                    onComplete: function() {
                        counter++;
                        send = true;
                        $('#send').prop('disabled', false);
                        $('#send').html('Send <i class="bi-send"></i>');
                        $('#cancel').hide();
                        $('#send').show();
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function clear(){
        if(typed){
            typed.stop();
        }
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: "mx-1 btn btn-success",
              cancelButton: "mx-1 btn btn-danger"
            },
            buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "Your conversation history will be cleared. You won't be able to revert this action",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, reset it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'clear',
                    success: function(response) {
                        Swal.fire({
                            title: "Clear Success!",
                            text: "Your conversation history has been cleared. Now you can start a new conversation.",
                            icon: "success"
                        });
                        counter = 0;
                        send = true;
                        $('#cancel').hide();
                        $('#send').prop('disabled', false);
                        $('#send').show();
                        $('#user_prompt').val('');
                        $('#prompt-container').empty();
                        $('#response-container').empty();
                        $('#prompt-container').hide();
                        $('#response-container').hide();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
              Swal.fire({
                title: "Cancelled",
                text: "Your conversation history is safe :)",
                icon: "error"
              });
            }
        });
    }

    function newPrompt(){
        var container = document.getElementById("response-container");
        var newElement = document.createElement("div"); 
        newElement.id = "prompt" + counter; 
        newElement.className = "prompt form-control mt-2 h-auto"; 
        newElement.style = "background-color: #333333; min-height: 2rem; cursor: default; color: #ffffff; border: none; resize: none; overflow: hidden;";
        newElement.readOnly = true;
        container.insertBefore(newElement, container.firstChild);
    }

    function newResponse(){
        var container = document.getElementById("response-container");
        var newElement = document.createElement("div"); 
        newElement.id = "response" + counter; 
        newElement.className = "response form-control mt-2";
        container.insertBefore(newElement, container.firstChild.nextSibling);
    }

    function sendPrompt(){
        $('#response-container').animate({ scrollTop: 0 }, 500);
        if (!send) {
            Swal.fire({
                title: "Uhm, you're getting too fast!",
                text: "Please wait for the response before sending another prompt.",
                icon: "warning"
            });
            return;
        } else {
            $('#send').prop('disabled', true);
            $('#send').hide();
            $('#cancel').show();
            send = false;
            newPrompt();
            userInput = $('#user_prompt').val();
            displayedPrompt = nl2brAndEscapeHtml(userInput);
            $('#prompt' + counter).html(displayedPrompt);
            newResponse();
            $('#response-container').show();
            getResponse();
        }
    }

    $('#send').click(function() {
        sendPrompt();
    });

    var isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0);
    if (!isTouchDevice) {
        $('#user_prompt').keypress(function(e) {
            if (e.which == 13) {
                if (!e.shiftKey) {
                    sendPrompt();
                    e.preventDefault();
                }
            }
        });
    }

    $('#cancel').click(function() {
        if (typed){
            typed.stop();
        }
        $('#send').prop('disabled', false);
        $('#send').show();
        $('#cancel').hide();
        send = true;
        counter--;
    });

    $('#reset').click(function() {
        clear();
    });
    
    var btnBackToTop = document.getElementById("btn-back-to-top");
    window.onscroll = function() {
        scrollFunction();
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            btnBackToTop.style.animationPlayState = "running";
            btnBackToTop.style.animationName = "fadeIn";
        } else {
            btnBackToTop.style.animationPlayState = "running";
            btnBackToTop.style.animationName = "fadeOut";
        }
    }

    btnBackToTop.addEventListener("click", function() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    });

});