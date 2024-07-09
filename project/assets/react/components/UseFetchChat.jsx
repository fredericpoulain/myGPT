import {useEffect, useState} from "react";

export function UseFetchChat(URL, setChat, options = {}) {
    const [loading, setLoading] = useState(true)
    const [errors, setErrors] = useState(null)


    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(URL, {
                    ...options,
                    headers: {'Accept': 'application/json; charset=UTF-8', ...options.headers}
                });
                if (!response.ok) throw new Error(`Une erreur est survenue: ${response.status}`);
                const data = await response.json();
                console.log('useEffect : ');
                console.log(data);
                setChat(data)
            } catch (error) {
                setErrors(error.message)
            } finally {
                setLoading(false)
            }
        }
        fetchData()
    }, []);
    return {loading, errors}
}