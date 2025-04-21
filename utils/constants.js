export const months = ["January", "February", "March", "April", "May", "June", "July",
    "August", "September", "October", "November", "December"];

export function formatDate(givenDate) {
    // Get the individual components
    const date = new Date(givenDate);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
    const day = String(date.getDate()).padStart(2, '0');
    // Construct the desired format
    return `${year}-${month}-${day}`;
}