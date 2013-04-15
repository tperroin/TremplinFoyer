<?php

/* WebProfilerBundle:Profiler:base_js.html.twig */
class __TwigTemplate_d04950f1a09106abea1efbf9817b0b53 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<script type=\"text/javascript\">/*<![CDATA[*/
    Sfjs = (function() {
        \"use strict\";

        var noop = function() {},
            request = function(url, onSuccess, onError, payload, options) {
                var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                options = options || {};
                xhr.open(options.method || 'GET', url, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onreadystatechange = function(state) {
                    if (4 === xhr.readyState && 200 === xhr.status) {
                        (onSuccess || noop)(xhr);
                    } else if (4 === xhr.readyState && xhr.status != 200) {
                        (onError || noop)(xhr);
                    }
                };
                xhr.send(payload || '');
            },
            hasClass = function(el, klass) {
                return el.className.match(new RegExp('\\\\b' + klass + '\\\\b'));
            },
            removeClass = function(el, klass) {
                el.className = el.className.replace(new RegExp('\\\\b' + klass + '\\\\b'), ' ');
            },
            addClass = function(el, klass) {
                if (!hasClass(el, klass)) { el.className += \" \" + klass; }
            };

        return {
            hasClass: hasClass,
            removeClass: removeClass,
            addClass: addClass,
            request: request,
            load: function(selector, url, onSuccess, onError, options) {
                var el = document.getElementById(selector);

                if (el && el.getAttribute('data-sfurl') !== url) {
                    request(
                        url,
                        function(xhr) {
                            el.innerHTML = xhr.responseText;
                            el.setAttribute('data-sfurl', url);
                            removeClass(el, 'loading');
                            (onSuccess || noop)(xhr, el);
                        },
                        function(xhr) { (onError || noop)(xhr, el); },
                        options
                    );
                }

                return this;
            },
            toggle: function(selector, elOn, elOff) {
                var i,
                    style,
                    tmp = elOn.style.display,
                    el = document.getElementById(selector);

                elOn.style.display = elOff.style.display;
                elOff.style.display = tmp;

                if (el) {
                    el.style.display = 'none' === tmp ? 'none' : 'block';
                }

                return this;
            }

        }
    })();
/*]]>*/</script>
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Profiler:base_js.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  91 => 20,  84 => 19,  74 => 16,  55 => 13,  44 => 13,  25 => 4,  105 => 24,  98 => 22,  96 => 21,  93 => 20,  89 => 19,  83 => 18,  76 => 16,  72 => 14,  68 => 12,  50 => 8,  33 => 5,  27 => 4,  24 => 2,  22 => 2,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 78,  186 => 76,  180 => 72,  178 => 71,  172 => 67,  163 => 63,  159 => 61,  154 => 59,  147 => 55,  143 => 54,  136 => 50,  130 => 47,  127 => 46,  121 => 45,  118 => 44,  114 => 43,  104 => 36,  100 => 34,  95 => 31,  78 => 28,  63 => 24,  34 => 11,  19 => 1,  94 => 39,  88 => 6,  81 => 40,  79 => 17,  35 => 7,  21 => 2,  32 => 4,  29 => 3,  224 => 72,  206 => 55,  203 => 54,  193 => 47,  189 => 77,  185 => 45,  181 => 44,  174 => 41,  164 => 34,  157 => 60,  150 => 21,  146 => 20,  141 => 19,  138 => 51,  132 => 48,  128 => 14,  124 => 13,  119 => 12,  116 => 11,  110 => 9,  97 => 91,  77 => 73,  75 => 27,  71 => 26,  69 => 54,  66 => 15,  64 => 41,  59 => 22,  57 => 14,  48 => 19,  46 => 7,  41 => 9,  36 => 7,  26 => 3,  80 => 31,  67 => 24,  60 => 23,  58 => 9,  51 => 12,  43 => 7,  39 => 6,  31 => 6,  28 => 2,);
    }
}
