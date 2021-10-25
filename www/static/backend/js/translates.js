(function () {
  function getLangCode() {
    const name = "TranslatesFilterForm[name][]";
    const value = new RegExp(
      "[?&]" + encodeURIComponent(name) + "=([^&]*)"
    ).exec(location.search);
    return value ? decodeURIComponent(value[1]) : null;
  }

  $(document).ready(function () {
    const $uploadTranslatesForm = $(".upload-translations-form");

    $uploadTranslatesForm.submit(function (e) {
      e.preventDefault();

      const data = new FormData();
      const $input = $uploadTranslatesForm.find(
        'input[name="translates-list"]'
      );
      if (!$input) {
        return;
      }
      const $files = $input[0].files;
      const lang_code = $input[0].id;
      if (!lang_code) {
        return;
      }

      $.each($files, function (i, file) {
        data.append("file-" + i, file);
      });
      $.ajax({
        url: `/admin/translates/upload-translates?lang_code=${lang_code}`,
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success() {
          document.location.reload();
        },
        error() {
          alert("Something went wrong");
        },
      });
    });
  });
})();
