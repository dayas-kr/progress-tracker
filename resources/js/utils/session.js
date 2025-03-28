export function setItemWithExpiration(key, value, expirationInSeconds) {
    const expirationTime = new Date().getTime() + expirationInSeconds * 1000; // expiration time in milliseconds
    const data = {
        value: value,
        expiration: expirationTime,
    };
    localStorage.setItem(key, JSON.stringify(data));
}

export function getItemWithExpiration(key) {
    const data = JSON.parse(localStorage.getItem(key));

    if (!data) {
        return null; // No data stored for this key
    }

    const currentTime = new Date().getTime();
    if (currentTime > data.expiration) {
        deleteSessionItem(key); // Remove the expired item
        return null; // Return null to indicate expiration
    }

    return data.value; // Data is still valid, return the stored value
}

// delete item from local storage
export function deleteSessionItem(key) {
    localStorage.removeItem(key);
}
