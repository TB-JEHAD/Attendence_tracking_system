import React from 'react';
import { View, StyleSheet } from 'react-native';
import { Button } from 'react-native-paper';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';

type HomeScreenProps = {
  navigation: NativeStackNavigationProp<any>;
};

const HomeScreen = ({ navigation }: HomeScreenProps) => {
  return (
    <View style={styles.container}>
      <Button
        mode="contained"
        onPress={() => navigation.navigate('AddStudent')}
        style={styles.button}
      >
        Add New Student
      </Button>
      <Button
        mode="contained"
        onPress={() => navigation.navigate('Attendance')}
        style={styles.button}
      >
        Take Attendance
      </Button>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 16,
    justifyContent: 'center',
  },
  button: {
    marginVertical: 8,
  },
});

export default HomeScreen; 