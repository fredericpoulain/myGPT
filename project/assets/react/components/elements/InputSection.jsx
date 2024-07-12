import React, { useState, useRef, useEffect } from "react";

export default function InputSection({ addMessage }) {
    const [message, setMessage] = useState("");
    const textareaRef = useRef(null);
    const maxHeight = 200; // Définir la hauteur maximale en pixels

    useEffect(() => {
        const textarea = textareaRef.current;
        if (textarea) {
            // Réinitialiser la hauteur pour obtenir la hauteur correcte lors du recalcul
            textarea.style.height = "auto";
            // Calculer la nouvelle hauteur
            let newHeight = Math.min(textarea.scrollHeight, maxHeight);
            textarea.style.height = newHeight + "px";
        }
    }, [message]);

    const handleSubmit = (e) => {
        e.preventDefault();
        if (message.trim() !== "") {
            addMessage(message);
            setMessage("");
        }
    };

    return (
        <div className="inputSection">
            <form className="formAddMessage" onSubmit={handleSubmit}>
                <textarea
                    ref={textareaRef}
                    rows="4"
                    value={message}
                    onChange={(e) => setMessage(e.target.value)}
                    style={{ maxHeight: maxHeight + "px", overflow: "hidden" }}
                ></textarea>
                <div className="submitIcon">
                    <button type="submit">
                        <i className="fa-solid fa-turn-up"></i>
                    </button>
                </div>
            </form>
        </div>
    );
}


//
// import React, {useState} from "react";
//
// export default function InputSection({addMessage}) {
//     const [message, setMessage] = useState("");
//
//
//     const handleSubmit = (e) => {
//         e.preventDefault();
//         if (message.trim() !== "") {
//             addMessage(message);
//             setMessage("");
//         }
//     };
//
//     return (
//         <div className="inputSection">
//             <form className="formAddMessage" onSubmit={handleSubmit}>
//                 <textarea
//                     rows="4"
//                     value={message}
//                     onChange={(e) => setMessage(e.target.value)}
//                 ></textarea>
//                 <div className="submitIcon">
//                     <button type="submit">
//                         <i className="fa-solid fa-turn-up"></i>
//                     </button>
//                 </div>
//             </form>
//         </div>
//     );
// }
