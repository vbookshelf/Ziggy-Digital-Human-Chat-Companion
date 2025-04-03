# Ziggy – A Digital Human Chat Companion
Ziggy is a prototype AI-powered conversational partner designed for engaging, intelligent and relaxed discussions.<br>
Supports both text and voice.

Live Demo:<br>
(Please use the Chrome browser.)<br>
https://ziggy.voicebot.woza.work/

<br>

## Notes

- This project demonstrates a simple and cheap way to create a personal voicebot using only HTML, CSS, Javascript and PHP.
- When using the voice chat I suggest wearing earphones or headphones with a mic. This will make the voice input more stable.
- Ziggy is powered by Llama3 70b running on Groq (free version). The number of tokens per chat session is limited.
- Example discussion topic: Are we living in a simulation?

## Design

Ziggy is meant to be a baseline digital human that can be improved. For example you could add long term memory, a better text to speech system, an avatar with lip syncing etc. That said, I'm finding that adding more features doesn't seem to improve the experience significantly. It just complicates the code. After chatting with Ziggy you adjust to it's quirks. One of Ziggy's best features is the speed at which it works i.e. low latency. This feature is appreciated much more than a flawless human sounding voice.

To date I've identified three features that I think are most important:
- A powerful LLM
- Low latency
- A warm (smiley) voice

## Aside

A tip for companies that are creating voices for text to speech applications: Ask the voice models to smile when the voices are being recorded. This will add warmth to the voice. If I remember correctly, it's a trick that salespeople use when speaking on the phone.

## Inspiration

The following fictional digital characters served as the inspiration for Ziggy:
- Samantha from the movie "Her"
- KiTT from the "Knight Rider" series
- Jarvis from the movie "Iron Man"

<br>

## Javascript Voicebot
A Javascript voicebot is available here:<br>
(This repo also includes a detailed writeup)<br>
https://github.com/vbookshelf/Khuluma-Javascript-Voicebot-ChatGPT

<br>

## Revision History

Version 2.0<br>
1-May-2024<br>
1- Replaced ChatGPT with Llama3-70b running on Groq.<br>
2- Removed csv file saving and loading.<br>


Version 1.1<br>
25-May-2023<br>
1- Added code to save a chat as a csv file.<br>
2- Added code to load a saved chat as a csv file.<br>
3- Building in these features adds complexity to the code because variables need to be moved<br>
between Javascript and PHP. If the saving and reloading features are not needed then v1.0<br>
is a more robust choice.

Version 1.0<br>
24-May-2023<br>
First release<br>
1- Basic setup that does not include the "Save chat" feature<br>
2- Voicechat works on Mac but it's unstable on Windows and Android.
