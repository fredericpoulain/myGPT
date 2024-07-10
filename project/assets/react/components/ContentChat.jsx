import React, {useEffect, useState} from "react";
import InputSection from "./elements/InputSection";
import MessageList from "./elements/MessageList";
import Scroll from "./elements/svg/Scroll";

export default function ContentChat({ isAddingMessage, chat, addMessage, canScroll, setCanScroll}) {

    // //si isAddingMessage === false ET QUE canScroll === true
    // if (!isAddingMessage && canScroll){
    //     console.log("Scroller !")
    //     //il faut afficher le composant <Scroll/> sous le composant InputSection
    //     //ensuite le composant <Scroll/> disparait si l'utilisateur à effectuer une action de scroll vers le bas
    //     //et setCanScroll repasse à false
    // }


    const [showScroll, setShowScroll] = useState(false);

    useEffect(() => {
        const checkScroll = () => {
            if (document.documentElement.scrollHeight > window.innerHeight) {
                setShowScroll(true);
                setCanScroll(true);
            } else {
                setShowScroll(false);
                setCanScroll(false);
            }
        };

        const handleScroll = () => {
            const scrollPosition = window.innerHeight + window.scrollY;
            const threshold = document.documentElement.scrollHeight - 50;

            if (scrollPosition >= threshold) {
                setShowScroll(false);
                setCanScroll(false);
            } else {
                setShowScroll(true);
                setCanScroll(true);
            }
        };

        checkScroll();  // Initial check
        window.addEventListener('resize', checkScroll);
        window.addEventListener('scroll', handleScroll);

        return () => {
            window.removeEventListener('resize', checkScroll);
            window.removeEventListener('scroll', handleScroll);
        };
    }, [chat, setCanScroll]);

    return (
        <div className="contentChat">
            <MessageList isAddingMessage={isAddingMessage} messages={chat} />
            { showScroll && !isAddingMessage && canScroll && <Scroll /> }
            <InputSection addMessage={addMessage}/>
        </div>
    );
}
