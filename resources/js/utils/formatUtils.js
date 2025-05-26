export function formatDate(strDate, locale = "pt-BR") {
    const date = new Date(strDate);
    if (isNaN(date.getTime())) return "";
    return date.toLocaleDateString(locale);
}

export function formatDatetime(
    strDate = "",
    {
        locale = "pt-BR",
        seconds = true,
        toTz = true,
        tz = "America/Sao_Paulo",
    } = {}
) {
    const isoDate = strDate.endsWith("Z") ? strDate : strDate + "Z";
    const date = new Date(isoDate);
    if (isNaN(date.getTime())) return "";

    if (!toTz) {
        const timeFormat = seconds
            ? {
                  hour: "2-digit",
                  minute: "2-digit",
                  second: "2-digit",
              }
            : {
                  hour: "2-digit",
                  minute: "2-digit",
              };
        const formattedDate = date.toLocaleDateString(locale);
        const formattedTime = date.toLocaleTimeString(locale, timeFormat);

        return `${formattedDate} ${formattedTime}`;
    }
    return date.toLocaleString(locale, { timeZone: tz }).replace(",", "");
}

export function formatPercent(number, locale = "pt-BR") {
    const parsed = parseFloat(number);
    if (isNaN(parsed)) return "-";

    const formatter = new Intl.NumberFormat(locale, {
        style: "percent",
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    });
    return formatter.format(parseFloat(number) / 100);
}

export function formatDecimal(number, locale = "pt-BR") {
    const parsed = parseFloat(number);
    if (isNaN(parsed)) return "-";

    const formatter = new Intl.NumberFormat(locale, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
    });
    return formatter.format(parseFloat(number));
}

export function formatCurrency(number, currency = "BRL", locale = "pt-BR") {
    const parsed = parseFloat(number);
    if (isNaN(parsed)) return "-";

    const formatter = new Intl.NumberFormat(locale, {
        style: "currency",
        currency: currency,
        useGrouping: true,
    });
    return formatter.format(parseFloat(number));
}
