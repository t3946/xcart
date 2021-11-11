export function number_format(
  number,
  decimals = 0,
  dec_point = ".",
  thousands_sep = ","
) {
  const sign = number < 0 ? "-" : "";
  const s_number =
    Math.abs(parseInt((number = (+number || 0).toFixed(decimals)))) + "";
  const len = s_number.length;
  const tchunk = len > 3 ? len % 3 : 0;

  const ch_first = tchunk ? s_number.substr(0, tchunk) + thousands_sep : "";
  const ch_rest = s_number
    .substr(tchunk)
    .replace(/(\d\d\d)(?=\d)/g, "$1" + thousands_sep);
  const ch_last = decimals
    ? dec_point + (Math.abs(number) - s_number).toFixed(decimals).slice(2)
    : "";
  return sign + ch_first + ch_rest + ch_last;
}
