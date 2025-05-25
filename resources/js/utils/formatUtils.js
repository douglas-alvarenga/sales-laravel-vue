export function formatDate(dateString, locale = "pt-BR") {
    const date = new Date(dateString);
    return date.toLocaleDateString(locale);
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
