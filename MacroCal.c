#include <stdio.h>
#include <math.h>

void ShowMenu() {
    // Function to show the menu (you already defined it)
    printf("1 For Cut\n");
        printf("2 For Bulk\n");
	 	   printf("3 For Maintain\n");

}

float BMI(int a, int w, int h) {
    // Convert height to meters
    float hm = h / 100.0;
    // Calculate BMI
    return w / (hm * hm);
}

float CalculateProteinIntake(int w, int plan) {
    // Function to calculate protein intake based on the plan
    if (plan == 1) { // Cutting
        return w / 1.5; // Higher protein intake for cutting
    } else if (plan == 2) { // Bulking
        return w / 1.7; // Standard protein intake for bulking
    } else { // Maintaining
        return w / 1.6; // Moderate protein intake for maintaining
    }
}

float CalculateCalories(int w, float activityLevel, int plan) {
    // Function to calculate daily caloric intake based on the plan
    if (plan == 1) { // Cutting
        return w * activityLevel + 600; // Higher caloric intake for cutting
    } else if (plan == 2) { // Bulking
        return w * activityLevel + 400; // Standard caloric intake for bulking
    } else { // Maintaining
        return w * activityLevel + 200; // Slightly higher caloric intake for maintaining
    }
}

float CalculateFats(float totalCalories, int plan) {
    // Function to calculate fat intake based on the plan
    if (plan == 1) { // Cutting
        return 0.2 * totalCalories / 9; // Lower fat intake for cutting
    } else if (plan == 2) { // Bulking
        return 0.3 * totalCalories / 9; // Standard fat intake for bulking
    } else { // Maintaining
        return 0.25 * totalCalories / 9; // Moderate fat intake for maintaining
    }
}

float CalculateCarbohydrates(float totalCalories, float proteinIntake, float fatIntake) {
    // Function to calculate carbohydrate intake
    float remainingCalories = totalCalories - (proteinIntake * 4 + fatIntake * 9);
    return remainingCalories / 4; // 1 gram of carbohydrate = 4 calories
}

int main() {
    int age, weight, height, plan;
    age = 18;
    weight = 67; // Sample weight in kg
    height = 179; // Sample height in cm

    // Calculate BMI
    float Bmc = BMI(age, weight, height);
    printf("Your BMI: %f\n", Bmc);

    // Determine the bulking plan based on BMI
    if (Bmc < 18.5) {
        printf("Underweight\n");
        printf("You Need To Bulk\n");
        plan = 2; // Set plan to bulking
    } else if (Bmc >= 18.5 && Bmc <= 24.9) {
        printf("Normal weight\n");
        printf("What's your next plan?\n");
        ShowMenu();
        scanf("%d", &plan); // Ask the user to choose the plan
    } else if (Bmc >= 25 && Bmc < 29.9) {
        printf("Overweight\n");
        plan = 1; // Set plan to cutting
    } else {
        printf("Obese\n");
        plan = 1; // Set plan to cutting
    }

    // Adjust nutrient intake based on the plan
    float activityLevel;
    if (plan == 1) { // Cutting
        activityLevel = 1.75; // Higher activity level for cutting
    } else if (plan == 2) { // Bulking
        activityLevel = 1.55; // Standard activity level for bulking
    } else { // Maintaining
        activityLevel = 1.6; // Slightly higher activity level for maintaining
    }

    // Calculate protein intake
    float proteinIntake = CalculateProteinIntake(weight, plan);
    printf("Protein Intake: %.2f grams/day\n", proteinIntake);

    // Calculate total daily calories for bulking
    float totalCalories = CalculateCalories(weight, activityLevel, plan);
    printf("Total Daily Calories: %.0f calories/day\n", totalCalories);

    // Calculate fat intake (30% of total calories)
    float fatIntake = CalculateFats(totalCalories, plan);
    printf("Fat Intake: %.2f grams/day\n", fatIntake);

    // Calculate carbohydrate intake
    float carbohydrateIntake = CalculateCarbohydrates(totalCalories, proteinIntake, fatIntake);
    printf("Carbohydrate Intake: %.2f grams/day\n", carbohydrateIntake);

    return 0;
}

