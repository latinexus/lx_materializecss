/**
 * Creator: Eric Larrea
 * E-mail: eric@latinex.us
 * From: latinex.us
 * Date: 06/12/25
 * Time: 04:46
 * Proyecto: cp_materializecss
 */

/**
 * tailCli.js
 * Inicializa comportamientos mínimos para los componentes TailCss:
 * - Collapsible toggles (data-collapse-target)
 * - Inputs file: mostrar nombre de fichero en un input hermano con clase .file-path
 * - Funciones utilitarias expuestas en window.TailCli
 */

(function () {
  'use strict';

  function toggleCollapse(button) {
    var targetId = button.getAttribute('data-collapse-target');
    if (!targetId) return;
    var target = document.getElementById(targetId);
    if (!target) return;

    var expanded = button.getAttribute('aria-expanded') === 'true';
    if (expanded) {
      button.setAttribute('aria-expanded', 'false');
      target.setAttribute('aria-hidden', 'true');
      target.classList.add('hidden');
    } else {
      button.setAttribute('aria-expanded', 'true');
      target.setAttribute('aria-hidden', 'false');
      target.classList.remove('hidden');
    }
  }

  function initCollapsibles(root) {
    root = root || document;
    var buttons = root.querySelectorAll('[data-collapse-target]');
    buttons.forEach(function (btn) {
      // ensure accessible attributes
      var targetId = btn.getAttribute('data-collapse-target');
      var target = document.getElementById(targetId);
      if (target) {
        if (!btn.hasAttribute('aria-expanded')) btn.setAttribute('aria-expanded', 'false');
        if (!target.hasAttribute('aria-hidden')) target.setAttribute('aria-hidden', 'true');
        // attach click
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          toggleCollapse(btn);
        });
      }
    });
  }

  function initFileInputs(root) {
    root = root || document;
    // match common file-field structure: input[type=file] and a sibling .file-path-wrapper input
    var fileInputs = root.querySelectorAll('input[type=file]');
    fileInputs.forEach(function (file) {
      file.addEventListener('change', function (e) {
        var input = e.currentTarget;
        var files = input.files;
        var name = '';
        if (files && files.length > 1) {
          name = files.length + ' files selected';
        } else if (files && files.length === 1) {
          name = files[0].name;
        }

        // buscar input con clase .file-path-wrapper input o .file-path
        var wrapper = input.closest('.file-field');
        if (wrapper) {
          var pathInput = wrapper.querySelector('.file-path, .file-path-wrapper input, input.file-path');
          if (pathInput) {
            pathInput.value = name;
            // trigger input event in case some code listens
            var ev = new Event('input', { bubbles: true });
            pathInput.dispatchEvent(ev);
          }
        } else {
          // fallback: buscar próximo input text en el DOM
          var next = input.nextElementSibling;
          while (next) {
            if (next.tagName === 'INPUT' && (next.type === 'text' || next.type === 'search')) {
              next.value = name;
              break;
            }
            next = next.nextElementSibling;
          }
        }
      });
    });
  }

  function initTailCli(root) {
    root = root || document;
    initCollapsibles(root);
    initFileInputs(root);
  }

  // auto-init on DOMContentLoaded
  document.addEventListener('DOMContentLoaded', function () {
    try { initTailCli(document); } catch (err) { console.error('TailCli init error', err); }
  });

  // expose API
  window.TailCli = {
    init: initTailCli,
    toggleCollapse: toggleCollapse
  };

})();
