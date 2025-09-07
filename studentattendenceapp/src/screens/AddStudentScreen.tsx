import React, { useState } from 'react';
import { View, StyleSheet, ScrollView } from 'react-native';
import { TextInput, Button, Snackbar } from 'react-native-paper';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';

type AddStudentScreenProps = {
  navigation: NativeStackNavigationProp<any>;
};

const AddStudentScreen = ({ navigation }: AddStudentScreenProps) => {
  const [name, setName] = useState('');
  const [rollNo, setRollNo] = useState('');
  const [visible, setVisible] = useState(false);
  const [message, setMessage] = useState('');

  const handleSubmit = async () => {
    if (!name || !rollNo) {
      setMessage('Please fill all fields');
      setVisible(true);
      return;
    }

    try {
      const existingStudents = await AsyncStorage.getItem('students');
      const students = existingStudents ? JSON.parse(existingStudents) : [];
      
      const newStudent = {
        id: Date.now().toString(),
        name,
        rollNo,
      };

      await AsyncStorage.setItem('students', JSON.stringify([...students, newStudent]));
      setMessage('Student added successfully');
      setVisible(true);
      setName('');
      setRollNo('');
      
      setTimeout(() => {
        navigation.goBack();
      }, 1500);
    } catch (error) {
      setMessage('Error adding student');
      setVisible(true);
    }
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.form}>
        <TextInput
          label="Student Name"
          value={name}
          onChangeText={setName}
          style={styles.input}
        />
        <TextInput
          label="Roll Number"
          value={rollNo}
          onChangeText={setRollNo}
          style={styles.input}
          keyboardType="numeric"
        />
        <Button mode="contained" onPress={handleSubmit} style={styles.button}>
          Add Student
        </Button>
      </View>
      <Snackbar
        visible={visible}
        onDismiss={() => setVisible(false)}
        duration={3000}
      >
        {message}
      </Snackbar>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  form: {
    padding: 16,
  },
  input: {
    marginBottom: 16,
  },
  button: {
    marginTop: 8,
  },
});

export default AddStudentScreen; 