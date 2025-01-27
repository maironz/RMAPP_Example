// RMTranslationHelpers.js
class RMTranslationHelpers {
  constructor(translations) {
      this.translations = translations || {};
  }

  /**
   * Get the translation for a specific key.
   * @param {string} key - The key for the desired translation.
   * @param {string} [defaultValue] - The default value if the key is not found.
   * @returns {string} - The translated string or the default value.
   */
  getTranslation(key, defaultValue = 'ND') {
      return this.translations[key] || defaultValue;
  }

  /**
   * Check if a translation key exists.
   * @param {string} key - The key to check.
   * @returns {boolean} - True if the key exists, false otherwise.
   */
  hasTranslation(key) {
      return Object.prototype.hasOwnProperty.call(this.translations, key);
  }
}