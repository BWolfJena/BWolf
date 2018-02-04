######
# Set working directory + Libraries
######
#setwd("C:\\Users\\Greifvogel\\Programming\\Git\\BWolf\\DistributionExamples")
library(RJSONIO)
library(httr)
######
# Constants
######

# Number of courses
courses <- 15
# Number of Students
numberStudents <- 130 

maxPerCourse <- 10

url = 'localhost:1338';

######
# Helper
######

# Aggregates all values of the provided list of lists
aggregationMatrix <- function(listOfLists) {
  # Initialize matrix
  aggregation <- matrix(0, nrow = courses, ncol = courses)
  # For every student's preference list
  for (studentPref in listOfLists) {
    # Get the index of the preference
    indexCourse <- 1
    # For every preference in a student's preference list
    for (pref in studentPref) {
      # Increment the preference at the course by one
      aggregation[pref,indexCourse] <- aggregation[pref,indexCourse] + 1
      # Increment the course index
      indexCourse <- indexCourse + 1
    }
  }
  # Return the matrix
  return(aggregation)
}

distribute <- function(elections, lowest, weights) {
  requestBody <- '{ 
    "courses" : {';
  
  for( i in 1:courses) {
    id = i;
    min = 0;
    max = maxPerCourse;
    requestBody <- paste0(requestBody,'"',id,'" : { "id": ', id, ', "min":', min, ',"max": ', max,'}')
    if(i < courses) {
      requestBody <- paste0(requestBody, ',');
    } else {
      requestBody <- paste0(requestBody, '},');
    }
  }
  requestBody <- paste0(requestBody,
                        '"elections" :', toJSON(elections),',
                        "params" : {
                        "lowest": ',lowest,',
                        "weights" :', toJSON(weights), '
                        }
  }');
  requestBody

  res <- httr::POST(url = url,
                    httr::add_headers('Content-Type' = 'application/json'),
                    httr::add_headers('Accept' = 'application/json'),
                    body = requestBody,
                    encode = "json")
  return(content(res))
} 

######
# Density functions
######

# Calculates the normal distribution for x points
normalDistribution <- function(x, mu = (length(x)+1)/2, sigma = 1) {
  prefactor <- 1/sqrt(2*pi*(sigma^2))
  exponent <- - (x - mu)^2 / (2*sigma^2)
  return(prefactor * exp(exponent))
}

# Calculates a multivariat distribution for n normal distributions 
multivariatDistribution <- function(n, mu = rep(0, n), sigma = rep(1, n)) {
  # Create a list to save all distributions
  distributions <- rep(0, courses)
  # Calculate n normal distributions and divide each by n
  for (i in 1:n) {
    distributions <- distributions + normalDistribution(1:courses, mu[i], sigma[i])/n
  }
  # Return the sum of the distributions
  return(distributions)
}

######
# Probabilities
######

normalProbability <- normalDistribution(1:courses, sigma = 2)
uniformProbability <- rep(1/courses, courses)
multivariatProbability <- multivariatDistribution(3, mu = c(5, 15, 25), sigma = c(1, 2, 1))

######
# Sampling
######

# Courses are columns, Students are rows
uniformPreferences <- vector(mode="list", length=numberStudents)
normalPreferences <- vector(mode="list", length=numberStudents)
multivariatPreferences <- vector(mode="list", length=numberStudents)
uni <- matrix(0, nrow = numberStudents, ncol = courses)
normal <- matrix(0, nrow = numberStudents, ncol = courses)
multi <- matrix(0, nrow = numberStudents, ncol = courses)

rownames
# Sample for each student
for (i in 1:numberStudents) {
  uni[i, ] <- sample(1:courses, courses, prob = uniformProbability)
  normal[i, ] <- sample(1:courses, courses, prob = normalProbability)
  multi[i, ] <- sample(1:courses, courses, prob = multivariatProbability)
}
uniformPreferences <- as.list(data.frame(t(uni)))
normalPreferences <- as.list(data.frame(t(normal)))
multivariatPreferences <- as.list(data.frame(t(multi)))


preferencesList = c(list(uniformPreferences), list(normalPreferences))
preferencesListNames = list('Gleichverteilte Präferenzen', 'Normalverteilte Präferenzen')
for(i in 1:2 ) {
    elections = preferencesList[[i]]
    pdf(paste0(preferencesListNames[[i]],'.pdf'));
    barplot(t(aggregationMatrix(elections)), xlab="Kurs",axes=FALSE, ylab="Präferenz - je dunkler desto höher", col=colorRampPalette(c("red","orange","blue")))
    title(preferencesListNames[[i]])
    contentRes <- distribute(elections, 0, courses:1)
    barplot(t(as.matrix(contentRes$histCourses)), xlab='Kurs ID', ylab='Anzahl Teilnehmer',col='#052e5d')
    title('Kurshistogramm - Lineare Gewichte ohne Minimum')
    barplot(t(as.matrix(contentRes$histPreferences)), xlab='Präferenz', ylab='Anzahl Studenten', col='#052e5d')
    title('Präferenzhistogramm - Lineare Gewichte ohne Minimum')
    min <- contentRes$min +1
    contentRes <- distribute(elections, min, courses:1)
    if(length(contentRes) > 3) {
      barplot(t(as.matrix(contentRes$histCourses)), xlab='Kurs ID', ylab='Anzahl Teilnehmer', col='#052e5d')
      title('Kurshistogramm - Lineare Gewichte mit Minimum')
      barplot(t(as.matrix(contentRes$histPreferences)), xlab='Präferenz', ylab='Anzahl Studenten', col='#052e5d')
      title('Präferenzhistogramm - Lineare Gewichte mit Minimum')
      min <- min + 1
      contentRes <- distribute(elections, min, courses:1)
      if(length(contentRes) > 3) {
        barplot(t(as.matrix(contentRes$histCourses)), xlab='Kurs ID', ylab='Anzahl Teilnehmer', col='#052e5d')
        title('Kurshistogramm - Lineare Gewichte mit Minimum')
        barplot(t(as.matrix(contentRes$histPreferences)), xlab='Präferenz', ylab='Anzahl Studenten', col='#052e5d')
        title('Präferenzhistogramm - Lineare Gewichte mit Minimum')
      }
    }
    contentRes <- distribute(elections, 0, rev(2^courses-2^(courses:1)))
    barplot(t(as.matrix(contentRes$histCourses)), xlab='Kurs ID', ylab='Anzahl Teilnehmer', col='#052e5d')
    title('Kurshistogramm - Exponentielle Gewichte ohne Minimum')
    barplot(t(as.matrix(contentRes$histPreferences)), xlab='Präferenz', ylab='Anzahl Studenten', col='#052e5d')
    title('Präferenzhistogramm - Exponentielle Gewichte ohne Minimum')
    dev.off();
}



######
# JSON/CSV Export
######

# Export distritbution to JSON
exportJson <- toJSON(uniformPreferences)
write(exportJson, "uniformPreferences.json")
exportJson <- toJSON(normalPreferences)
write(exportJson, "normalPreferences.json")
exportJson <- toJSON(multivariatPreferences)
write(exportJson, "multivariatPreferences.json")
# Export aggregated to CSV
write.csv(aggregationMatrix(uniformPreferences), file = "aggregatedUniPref.csv")
write.csv(aggregationMatrix(normalPreferences), file = "aggregatedNormPref.csv")
write.csv(aggregationMatrix(multivariatPreferences), file = "aggregatedMultiPref.csv")
# Export distritbution to Csv
write.csv(uni, file = "uniformPreferences.csv")
write.csv(normal, file = "normalPreferences.csv")
write.csv(multi, file = "multiPreferences.csv")

######
# Plotting
######


plot(1:6, type='l', col='#052e5d')
plot(exp(6)-exp(6:1), type='l', col='#052e5d')

normalPreffAgg=aggregationMatrix(normalPreferences)
barplot(t(aggregationMatrix(uniformPreferences)))
barplot(t(aggregationMatrix(normalPreferences)))
barplot(t(aggregationMatrix(multivariatPreferences)))
