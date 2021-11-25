export default function (dateTime: string): Date {
  const [datePart, timePart] = dateTime.split(" ");
  const [year, month, date] = datePart.split("-");
  const [hour, minutes, seconds] = timePart.split(":");

  return new Date(
    parseInt(year),
    parseInt(month) - 1,
    parseInt(date),
    parseInt(hour),
    parseInt(minutes),
    parseInt(seconds)
  );
}
