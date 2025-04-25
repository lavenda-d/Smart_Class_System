# 🏫 Smart Classroom Attendance System

An **offline-capable** attendance system that uses **fingerprint authentication** to register and verify students in real-time. Built for smart classrooms, this project combines hardware and software to create a seamless, secure attendance experience.

---

## ✨ Features

- ✅ **Offline Functionality**  
  Works entirely offline — all data is processed and stored on a local laptop server.

- 🔐 **Biometric Authentication**  
  Uses fingerprint recognition for accurate student identification.

- 📲 **Smartphone Integration**  
  Fingerprint data is captured via a smartphone app and sent to the server (no local storage on the device).

- 💾 **Local Database Storage**  
  Student names and fingerprints are linked and stored on the laptop’s database.

- 📟 **Real-Time Feedback**  
  LCD displays status messages; RGB LED and buzzer give instant visual/audio cues.

---

## 🧰 Hardware Components

| Component          | Description                           |
|--------------------|---------------------------------------|
| ESP8266 NodeMCU    | Microcontroller & Wi-Fi communication |
| Fingerprint Sensor | Captures student fingerprints         |
| I2C LCD Display    | Shows status messages                 |
| RGB LED            | Indicates system status               |
| Buzzer             | Audio alert for feedback              |
| Smartphone /AS608     | Captures & sends fingerprint data     |
| Laptop             | Hosts server & database               |

---

## ⚙️ System Behavior

### On Power-Up
- RGB LED glows **green** for 1 second

### Registration Phase
1. Student's fingerprint captured via smartphone app
2. Data sent to server via Wi-Fi
3. Server links fingerprint with student's **name**
4. LCD displays: `Registration Successful`

### Attendance Phase
1. Fingerprint scanned via sensor
2. If **recognized**:
   - Green LED blinks **2x per second**
   - LCD shows: `Welcome, [Student Name]`
3. If **not recognized**:
   - Red LED blinks **3x per second**
   - Buzzer sounds in sync
   - LCD shows: `Fingerprint Not Found`

---

---

## 💡 Future Improvements

- Facial recognition integration
- RFID fallback system
- Web dashboard for attendance analytics
- Notification system for absent students

---

## 🧑‍💻 Authors

**[VICTOR]**  **[LAVENDA]**
Web & Embedded Systems Developers  
*Passionate about blending hardware and software for smarter education.*

---

