import React, { useState, useEffect } from 'react';
import { View, StyleSheet, ScrollView } from 'react-native';
import { List, Checkbox, Button, Snackbar } from 'react-native-paper';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';

type Student = {
  id: string;
  name: string;
  rollNo: string;
};

type AttendanceScreenProps = {
  navigation: NativeStackNavigationProp<any>;
};

const AttendanceScreen = ({ navigation }: AttendanceScreenProps) => {
  const [students, setStudents] = useState<Student[]>([]);
  const [attendance, setAttendance] = useState<{ [key: string]: boolean }>({});
  const [visible, setVisible] = useState(false);
  const [message, setMessage] = useState('');

  useEffect(() => {
    loadStudents();
  }, []);

  const loadStudents = async () => {
    try {
      const storedStudents = await AsyncStorage.getItem('students');
      if (storedStudents) {
        const parsedStudents = JSON.parse(storedStudents);
        setStudents(parsedStudents);
        // Initialize attendance state
        const initialAttendance = parsedStudents.reduce((acc: any, student: Student) => {
          acc[student.id] = false;
          return acc;
        }, {});
        setAttendance(initialAttendance);
      }
    } catch (error) {
      setMessage('Error loading students');
      setVisible(true);
    }
  };

  const toggleAttendance = (studentId: string) => {
    setAttendance(prev => ({
      ...prev,
      [studentId]: !prev[studentId]
    }));
  };

  const saveAttendance = async () => {
    try {
      const date = new Date().toISOString().split('T')[0];
      const attendanceData = {
        date,
        records: Object.entries(attendance).map(([studentId, present]) => ({
          studentId,
          present
        }))
      };

      const existingAttendance = await AsyncStorage.getItem('attendance');
      const allAttendance = existingAttendance ? JSON.parse(existingAttendance) : [];
      
      await AsyncStorage.setItem('attendance', JSON.stringify([...allAttendance, attendanceData]));
      
      setMessage('Attendance saved successfully');
      setVisible(true);
      
      setTimeout(() => {
        navigation.goBack();
      }, 1500);
    } catch (error) {
      setMessage('Error saving attendance');
      setVisible(true);
    }
  };

  return (
    <View style={styles.container}>
      <ScrollView>
        {students.map((student) => (
          <List.Item
            key={student.id}
            title={student.name}
            description={`Roll No: ${student.rollNo}`}
            right={() => (
              <Checkbox
                status={attendance[student.id] ? 'checked' : 'unchecked'}
                onPress={() => toggleAttendance(student.id)}
              />
            )}
          />
        ))}
      </ScrollView>
      <Button
        mode="contained"
        onPress={saveAttendance}
        style={styles.button}
      >
        Save Attendance
      </Button>
      <Snackbar
        visible={visible}
        onDismiss={() => setVisible(false)}
        duration={3000}
      >
        {message}
      </Snackbar>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  button: {
    margin: 16,
  },
});

export default AttendanceScreen; 