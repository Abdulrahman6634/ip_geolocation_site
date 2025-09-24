<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* public/pages/typeform.twig */
class __TwigTemplate_a94f9ba056ae27b1d7204c0ba7639017 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        yield "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Website & App Development Inquiry</title>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH\" crossorigin=\"anonymous\">
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css\">
    <link href=\"https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap\" rel=\"stylesheet\">
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"https://unpkg.com/notie/dist/notie.min.css\">
    <style>
        body {
            font-family: 'Karla', sans-serif;
            background-color: white; /* Dark background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .iti  {
            width: 100% !important;
            margin-top: 32px;
        }
        .navbar {
            background-color: white;
            position: absolute;
            top: 0;
            width: 100%;
            padding: 10px 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            /* Light border for contrast */

            background-color: white; /* Transparent background */
            /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); */
            color: black; /* Text color */
        }
        .step {
            display: none;
        }
        .step.active {
            display: block;
        }
        .form-control {
            border: none;
            border-bottom: 2px solid grey; /* Initial grey border */
            box-shadow: none;
            transition: border-color 0.3s;
            background-color: white;
            color: #000; /* Text color */
            border-radius: 0px;
            margin-top: 32px;
            outline: none; /* Remove default outline */
        }

        .form-control:focus {
            border-bottom: 2px solid black; /* Border color on focus */
            outline: none; /* Remove default outline */
            box-shadow: none; /* Remove any focus shadow */
        }
        .arrow-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .btn-arrow {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .yes , .no{
            margin-top: 32px;
        }
        input[type=\"radio\"]:checked + label {
            background-color: black; /* Bootstrap primary color */
            color: white;
        }

    </style>
</head>
<body>
<!-- Large Screen Navbar -->
<nav class=\"navbar navbar-expand-lg d-none d-lg-block\">
    <div class=\"container\">
        <div class=\"collapse navbar-collapse\" id=\"navbarNav\">
            <div class=\"justify-content-start\" style=\"width: 30%;\">
                <img width=\"65%\" src=\"https://res.cloudinary.com/dlqifgxtl/image/upload/v1724138640/Logo-Cosgn-it_orjtao.webp\" alt=\"Cosgn Logo\" class=\"d-inline-block align-text-top\">
            </div>
            <ul class=\"navbar-nav\">
                <li class=\"nav-item\">
                    <a class=\"nav-link text-dark\" href=\"/\">Home</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link text-dark\" href=\"#\">Cosgn Credit?</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link text-dark\" href=\"#\">Live Support</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Screen Navbar -->
<!-- Mobile Screen Navbar -->
<nav class=\"navbar navbar-light d-lg-none\">
    <div class=\"container d-flex justify-content-between align-items-center\">
        <a class=\"text-white\" href=\"#\">
            <img width=\"50%\" src=\"https://res.cloudinary.com/dlqifgxtl/image/upload/v1724138640/Logo-Cosgn-it_orjtao.webp\" alt=\"Cosgn Logo\">
        </a>
        <!-- Move the toggle button to the right -->
        <button style=\"border: none\" class=\"navbar-toggler ms-auto\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarMobileNav\" aria-controls=\"navbarMobileNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span style=\"width: 1.8em; height: 1.8em;\" class=\"navbar-toggler-icon\"></span>
        </button>
    </div>

    <div class=\"collapse navbar-collapse\" id=\"navbarMobileNav\">
        <ul class=\"navbar-nav ms-auto\">
            <li class=\"nav-item\">
                <a class=\"nav-link text-dark ps-5\" href=\"/\"
                   onmouseover=\"this.style.color='#007bff'; this.style.backgroundColor='#f8f9fa'; this.style.borderRadius='5px';\"
                   onmouseout=\"this.style.color=''; this.style.backgroundColor=''; this.style.borderRadius='';\">
                    Home
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link text-dark ps-5\" href=\"#\"
                   onmouseover=\"this.style.color='#007bff'; this.style.backgroundColor='#f8f9fa'; this.style.borderRadius='5px';\"
                   onmouseout=\"this.style.color=''; this.style.backgroundColor=''; this.style.borderRadius='';\">
                    Cosgn Credit?
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link text-dark ps-5\" href=\"#\"
                   onmouseover=\"this.style.color='#007bff'; this.style.backgroundColor='#f8f9fa'; this.style.borderRadius='5px';\"
                   onmouseout=\"this.style.color=''; this.style.backgroundColor=''; this.style.borderRadius='';\">
                    Live Support
                </a>
            </li>
        </ul>
    </div>
</nav>




<div class=\"container\">
    <form id=\"multiStepForm\" method=\"post\">
        <div class=\"step active\">
            <h3><i style=\"font-size:20px\" class=\"bi bi-arrow-right\"></i> Full Name*</h3>
            <div class=\"form-group\">
                <label for=\"name\" class=\"text-muted\">Please provide your full name. This helps us personalize our communication with you.</label>
                <input type=\"text\" class=\"form-control\" name=\"name\" id=\"name\" required>
            </div>
            <div class=\"arrow-buttons\">
                <button type=\"button\" class=\"btn btn-dark btn-lg next\">OK</button>
                <button type=\"button\" class=\"btn btn-sm btn-outline-dark btn-arrow down\">
                    <i class=\"bi bi-arrow-down-circle\"></i>
                </button>
            </div>
        </div>
        <div class=\"step\">
            <h3><i style=\"font-size:20px\" class=\"bi bi-arrow-right\"></i> Email Address*</h3>
            <div class=\"form-group\">
                <label for=\"email\" class=\"text-muted\">Enter your email address so we can send you updates and information related to your project.</label>
                <input type=\"email\" class=\"form-control\" name=\"email\" id=\"email\" required>
            </div>
            <div class=\"arrow-buttons\">
                <button type=\"button\" class=\"btn btn-outline-dark btn-sm btn-arrow up\">
                    <i class=\"bi bi-arrow-up-circle\"></i>
                </button>
                <button type=\"button\" class=\"btn btn-dark btn-lg next\" onclick=\"sendEmailCode()\">Send Code</button>
            </div>
        </div>
        <div class=\"step\">
            <h3><i style=\"font-size:20px\" class=\"bi bi-arrow-right\"></i> Verification Code*</h3>
            <div class=\"input-group mb-3\">
                <label for=\"email_verify_Code\" class=\"text-muted\">Please enter the verification code that was sent to your given email address. </label>
                <input type=\"text\" style=\"margin-top: 4px;\" class=\"form-control\" id=\"email_verify_Code\" placeholder=\"0000\" aria-label=\"Verification Code\" aria-describedby=\"Code\">
                <button class=\"btn btn-dark\" type=\"button\" id=\"resendCode\"><i class=\"bi bi-arrow-repeat\"></i></button>
            </div>
            <div id=\"error-message\" style=\"color: red; display: none;\">Invalid verification code. Please try again.</div>
            <div class=\"arrow-buttons\">
                <button type=\"button\" class=\"btn btn-light btn-arrow up\">
                    <i class=\"bi bi-arrow-up-circle\"></i>
                </button>
                <a type=\"button\" onclick=\"verifyEmail()\" class=\"btn btn-dark btn-lg\">Verify</a>
            </div>
        </div>


        <div class=\"step\">
            <h3><i style=\"font-size:20px\" class=\"bi bi-arrow-right\"></i> Phone Number*</h3>
            <div class=\"form-group\">
                <label for=\"phone\" class=\"text-muted\">Provide your phone number for direct communication. This will help us reach you quickly if we have any questions.</label>
                <input type=\"tel\" class=\"form-control\" id=\"phone\" required>
            </div>
            <div class=\"arrow-buttons\">
                <button type=\"button\" class=\"btn btn-light btn-arrow up\">
                    <i class=\"bi bi-arrow-up-circle\"></i>
                </button>
                <a type=\"button\" onclick=\"sendCode()\" class=\"btn btn-dark btn-lg \">Send Code</a>
            </div>
        </div>

        <div class=\"step\">
            <h3 style=\"margin-bottom: 28px;\"><i style=\"font-size:20px\" class=\"bi bi-arrow-right\"></i> Verification Code*</h3>

            <div class=\"input-group \">
                <label for=\"verify_Code\" class=\"text-muted \">Please enter the verification code that was sent to your given phone number. </label>
                <input type=\"text\" style=\"margin-top: 4px;\" class=\"form-control mb-3 mt-3\" id=\"sms_verify_code\" placeholder=\"0000\" aria-label=\"Verification Code\" aria-describedby=\"Code\">
                <button class=\"btn btn-dark\" type=\"button\" id=\"Code\" onclick=\"sendCode()\"><i class=\"bi bi-repeat\"></i></button>
            </div>
            <div class=\"arrow-buttons\">
                <button type=\"button\" class=\"btn btn-light btn-arrow up\">
                    <i class=\"bi bi-arrow-up-circle\"></i>
                </button>
                <a type=\"button\" onclick=\"verifySMS()\" class=\"btn btn-dark btn-lg\">Verify</a>
            </div>
        </div>

        <div class=\"step\">
            <h3> New Website or Upgrade?</h3>
            <div class=\"form-group\">
                <label class=\"text-muted\">Tell us if you need a brand-new website or improvements to your current one. You can also provide details about your project requirements to help us better understand your needs.</label><br>

                <div class=\"form-group mt-3\">
                    <textarea name=\"website_detail\" id=\"website_detail\" cols=\"65\" rows=\"10\" required></textarea>
                </div>
            </div>

            <div class=\"arrow-buttons\">
                <button type=\"button\" class=\"btn btn-light btn-arrow up\">
                    <i class=\"bi bi-arrow-up-circle\"></i>
                </button>
                <button type=\"button\" class=\"btn btn-dark btn-lg next\">OK</button>
            </div>
        </div>

        <div class=\"step\">
            <h3>How soon do you plan to start your project, and what is your budget range?</h3>
            <div class=\"form-group\">
                <div class=\"form-group\">
                    <label for=\"budget_range\" class=\"text-muted\">Please provide your Budget Range. This helps us personalize our communication with you.</label>
                    <input type=\"text\" class=\"form-control\" name=\"budget_range\" id=\"budget_range\" required>
                </div>
            </div>

            <div class=\"arrow-buttons\">
                <button type=\"button\" class=\"btn btn-light btn-arrow up\">
                    <i class=\"bi bi-arrow-up-circle\"></i>
                </button>
                <button type=\"button\" class=\"btn btn-dark btn-lg next\">OK</button>
            </div>
        </div>


        <div class=\"step\">
            <h3><i style=\"font-size:20px\" class=\"bi bi-arrow-right\"></i>Are you looking to pay upfront or use Cosgn Credit? Depending on your membership plan, Cosgn Credit offers cash back on expenses, free storage and hosting, free domains with yearly renewals, flexible payment options, and complimentary SEO optimization services.</h3>
            <div class=\"form-group\">
                <label class=\"text-muted\">Would you prefer to pay for your website upfront, or are you interested in using Cosgn credit for a more flexible payment schedule? This will help us understand your preferred payment method.</label><br>

                <div class=\"btn-group\" role=\"group\" aria-label=\"Payment Method\">
                    <input type=\"radio\" id=\"CosignCredit\" name=\"paymentMethod\" value=\"CosignCredit\" class=\"d-none\">
                    <label for=\"CosignCredit\" class=\"btn btn-outline-dark\">Cosign Credit</label>

                    <input type=\"radio\" id=\"Upfront\" name=\"paymentMethod\" value=\"Upfront\" class=\"d-none\">
                    <label for=\"Upfront\" class=\"btn btn-outline-dark\">Upfront</label>
                </div>
            </div>
            <div class=\"arrow-buttons\">
                <button type=\"button\" id=\"submitButton\" class=\"btn btn-dark btn-lg\" onclick=\"insert_tool()\">
                    <span id=\"buttonText\">Submit</span>
                    <span id=\"loader\" class=\"spinner-border spinner-border-sm ms-2 d-none\" role=\"status\" aria-hidden=\"true\"></span>
                </button>
            </div>


            <div id=\"result\"></div>

        </div>

    </form>
</div>
<script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>
<script src=\"https://unpkg.com/notie\"></script>
<script src=\"https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js\"></script>
<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js\"></script>
<script src=\"https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js\"></script>

<script>
    let leadData = {};
    let currentStep = 0;
    const steps = \$(\".step\");
    let isCodeSent = false;
    function navigateToNextStep() {
        \$(steps[currentStep]).removeClass('active'); // Hide the current step
        currentStep++; // Move to the next step
        if (currentStep < steps.length) {
            \$(steps[currentStep]).addClass('active'); // Show the next step
            getData();

            \$('html, body').animate({
                scrollTop: \$(steps[currentStep]).offset().top // Smooth scroll to the next step
            }, 500);
        }
    }
    function verifySMS(){
        const user_verify_code= \$('#sms_verify_code').val();
        const user_phone= \$('#phone').val();

        let formData = {
            verifyCode: user_verify_code,
            phone: user_phone,
        };
        \$.ajax({
            url: '/typeform/verify/sms',
            type: 'POST',
            data: formData,
            success: function(data) {
                data = JSON.parse(data);
                if (data.status === true) {
                    leadData[\"email_verify\"] = true;
                    notie.alert({ type: 'success', text: data.message, time: 10 });
                    navigateToNextStep();

                } else if (data.status === false) {
                    notie.alert({ type: 'warning', text: data.message, time: 10 });
                } else {
                    \$(\"#result\").html('<div class=\"alert alert-danger alert alert-warning alert-dismissible fade show\" role=\"alert\">Something went wrong! <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button></div>');
                }
            },
            error: function(error) {
                alert('An error occurred: ' + error);
            }
        });
    }

    function verifyEmail(){
        const email_verify_Code= \$('#email_verify_Code').val();
        if(email_verify_Code === Cookies.get('email_verify_Code')){
            leadData[\"email_verify\"] = true;
            notie.alert({ type: 'success', text: 'Verification code successfully verified.', time: 5 });
            navigateToNextStep();
        }else{
            notie.alert({ type: 'warning', text: 'Invalid Code', time: 5 });
        }
    }
    function sendEmailCode() {
        const email_verify_Code = generateUniqueVerificationCode();
        Cookies.set('email_verify_Code', email_verify_Code);

        let email = document.getElementById('email').value;

        let formData = {
            email: email,
            verifyCode: email_verify_Code,
        };
        console.log(formData)

        \$.ajax({
            url: '/typeform/email',
            type: 'POST',
            data: formData,
            success: function(data) {
                data = JSON.parse(data);
                if (data.status === true) {
                    notie.alert({ type: 'success', text: data.message });
                    // navigateToNextStep();
                } else if (data.status === false) {
                    notie.alert({ type: 'warning', text: data.message, time: 10 });
                } else {
                    \$(\"#result\").html('<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">Something went wrong! <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button></div>');
                }
            },
            error: function(error) {
                alert('An error occurred: ' + error);
            }
        });
    }

    function insert_tool() {
        getData();

        // Convert `leadData` into FormData
        var formData = new FormData();
        formData.append('full_name', leadData.full_name);
        formData.append('email', leadData.email);
        formData.append('phone', leadData.phone);
        formData.append('website_detail', leadData.website_detail);
        formData.append('budget_range', leadData.budget_range);
        formData.append('paymentMethod', leadData.paymentMethod);

        // Show the loader and hide the button text
        \$(\"#loader\").removeClass('d-none'); // Show loader
        \$(\"#buttonText\").addClass('d-none'); // Hide text
        \$(\"#submitButton\").prop('disabled', true); // Disable button

        \$.ajax({
            url: \"/typeform\",
            type: \"POST\",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                console.log(data);
                window.location.href = '/thank-you';
            },
            error: function () {
                \$(\"#result\").html('<div class=\"alert alert-danger alert alert-warning alert-dismissible fade show\" role=\"alert\">Something went wrong! <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button></div>');
            },
            complete: function () {
                // Hide the loader and show the button text
                \$(\"#loader\").addClass('d-none'); // Hide loader
                \$(\"#buttonText\").removeClass('d-none'); // Show text
                \$(\"#submitButton\").prop('disabled', false); // Enable button
            }
        });
    }




    // function verifySMS(){
    //     let sms_verify_code= \$('#sms_verify_code').val();
    // }
    function sendCode(\$flag=null) {
        if (isCodeSent) {
            notie.alert({ type: 'warning', text: 'Verification code has already been sent.', time: 5 });
            return; // Exit the function if code has already been sent
        }

        const verify_code = generateUniqueVerificationCode();
        Cookies.set('verify_code', verify_code);
        let phone = document.getElementById('phone').value;
        let formData = {
            phone: phone,
            verifyCode: verify_code,
        };

        \$.ajax({
            url: '/typeform/sms',
            type: 'POST',
            data: formData,
            success: function(data) {
                data = JSON.parse(data);
                if (data.status === true) {
                    notie.alert({ type: 'success', text: data.message, time: 10 });
                    isCodeSent = true; // Set the flag to true after sending the code

                       navigateToNextStep();

                } else if (data.status === false) {
                    notie.alert({ type: 'warning', text: data.message, time: 10 });
                } else {
                    \$(\"#result\").html('<div class=\"alert alert-danger alert alert-warning alert-dismissible fade show\" role=\"alert\">Something went wrong! <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button></div>');
                }
            },
            error: function(error) {
                alert('An error occurred: ' + error);
            }
        });
    }


    function validateForm(step) {
        const inputs = \$(step).find(\"input\");
        let isValid = true;
        inputs.each(function() {
            if (!this.checkValidity()) {
                isValid = false;
                this.reportValidity(); // Show the validation message
            }
        });
        return isValid;
    }

    \$(document).ready(function() {
        // Show the first step
        \$(steps[currentStep]).addClass('active');

        // Next button click
        \$(\".next\").click(function() {
            if (validateForm(steps[currentStep])) {
                navigateToNextStep();
                getData();
                console.log(leadData);

            }
        });

        // Up arrow button click
        \$(\".up\").click(function() {
            if (currentStep > 0) {
                \$(steps[currentStep]).removeClass('active');
                currentStep--;

                // Show the phone number step
                \$(steps[currentStep]).addClass('active');
                \$('html, body').animate({
                    scrollTop: \$(steps[currentStep]).offset().top
                }, 500);
            }
        });

        // Send Code button click
        \$('#sendCodeBtn').click(function() {
            sendCode();
        });

        // Reset code sent flag when the phone number changes
        \$('#phone').on('input', function() {
            isCodeSent = false; // Reset the flag when the user changes the phone number
        });
    });

    function generateUniqueVerificationCode() {
        // Generate a 6-digit random number
        let code = Math.floor(100000 + Math.random() * 900000);
        return code; // Return the generated code
    }

    // Initialize the international telephone input
    const phoneInputField = document.querySelector(\"#phone\");
    const phoneInput = window.intlTelInput(phoneInputField, {
        initialCountry: \"auto\",
        geoIpLookup: function(success, failure) {
            fetch('https://ipinfo.io?token=035b7c6a096209')
                .then(response => response.json())
                .then(data => success(data.country))
                .catch(() => success('US'));
        },
        utilsScript: \"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js\"
    });

    function getData(){
        leadData[\"full_name\"] = \$('#name').val();
        leadData[\"email\"] = \$('#email').val();
        leadData[\"phone\"] = \$('#phone').val();
        leadData[\"website_detail\"] = \$('#website_detail').val();
        leadData[\"budget_range\"] = \$('#budget_range').val();
        leadData[\"paymentMethod\"] = \$(\"input[name='paymentMethod']:checked\").val();

    }





</script>

</body>
</html>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "public/pages/typeform.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array ();
    }

    public function getSourceContext()
    {
        return new Source("", "public/pages/typeform.twig", "/home/abdul/Desktop/Website/store.local/views/public/pages/typeform.twig");
    }
}
