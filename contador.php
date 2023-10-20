<?php
/**
 * Plugin Name: ReallySimpleCounterWP
 * Plugin URI:  
 * Description: Adiciona contadores personalizados em diferentes partes das páginas. Use o shortcode [contador tag="h1" numero="100" tempo="1" id="contador1"].
 * Version: 1.0
 * Author: Pedro Luis Schultz
 * Author URI: github.com/PLSchultz
 */

function contador_shortcode($atts) {
    $atts = shortcode_atts(array(
        'tag' => 'span',
        'numero' => 1000,
        'tempo' => 10,
        'id' => uniqid('contador_')
    ), $atts, 'contador');

    $output = "<{$atts['tag']} class='contador contador-{$atts['id']}' data-final='{$atts['numero']}' data-tempo='{$atts['tempo']}' id='contador_numero_{$atts['id']}'>";
    $output .= "0";
    $output .= "</{$atts['tag']}>";

    $output .= "<script>
        document.addEventListener('DOMContentLoaded', function () {
            var contadorSpan = document.getElementById('contador_numero_{$atts['id']}');
            var final = parseFloat(contadorSpan.getAttribute('data-final'));
            var tempo = parseInt(contadorSpan.getAttribute('data-tempo')) * 1000; // Convertendo para milissegundos
            var incremento = final / tempo; // Incremento por milissegundo
            var contadorAtual = 0;
            var lastTimestamp = performance.now();

            function formatarNumero(numero) {
                if (Number.isInteger(numero)) {
                    return numero.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                } else {
                    var partes = numero.toFixed(2).split('.');
                    partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    return partes.join(',');
                }
            }

            function atualizarContador(timestamp) {
                contadorAtual += incremento * (timestamp - lastTimestamp);
                if (contadorAtual >= final) {
                    contadorAtual = final;
                } else {
                    requestAnimationFrame(atualizarContador);
                }
                contadorSpan.innerText = formatarNumero(contadorAtual);
                lastTimestamp = timestamp;
            }

            setTimeout(function() {
                requestAnimationFrame(atualizarContador);
            }, 0);
        });
    </script>";

    return $output;
}

add_shortcode('contador', 'contador_shortcode');
?>
