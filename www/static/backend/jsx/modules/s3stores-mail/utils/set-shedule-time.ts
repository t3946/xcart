import moment, { Moment } from "moment";

export function setScheduleTime(hours: number): Moment {
  if (!hours) return moment();
  let date: Date | Moment = new Date();

  date.setHours(hours);
  date.setMinutes(0);
  date.setSeconds(0);

  date = moment(date);

  if (date.day() === 5) {
    return date.add("3", "days");
  }

  return date.add("1", "days");
}

export function switchValue(value: string): number {
  switch (value) {
    case "1": {
      return 8;
    }
    case "2": {
      return 9;
    }
    default: {
      return 0;
    }
  }
}
