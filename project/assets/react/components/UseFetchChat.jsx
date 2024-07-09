import { useState } from "react";

export function UseFetchChat(url, setChat, options = {}) {
    const [loading, setLoading] = useState(true);
    const [errors, setErrors] = useState(null);

    const fetchChat = async () => {
        try {
            const response = await fetch(url, {
                ...options,
                headers: { 'Accept': 'application/json; charset=UTF-8', ...options.headers },
            });
            if (!response.ok) throw new Error(`Une erreur est survenue: ${response.status}`);
            const data = await response.json();
            setChat(data.messages || []);
        } catch (error) {
            setErrors(error.message);
        } finally {
            setLoading(false);
        }
    };

    return { loading, errors, fetchChat };
}
